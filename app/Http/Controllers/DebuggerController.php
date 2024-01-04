<?php
namespace App\Http\Controllers;

use App\Repositories\ErrorRepository;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Phpml\Tokenization\WordTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\StopWords\English;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use function Rap2hpoutre\RemoveStopWords\remove_stop_words;

class DebuggerController extends Controller
{
    //
    public function index()
    {
        return view('debug.index');
    }

    public function process(Request $request)
    {  
        // $test = Dataset::selectRaw('error_type, COUNT(*) as count')
        // ->groupBy('error_type')
        // ->distinct()
        // ->get()
        // ->toArray();

        // dd($test);

        $error = $request->input('message');

        $tf = $this->calculateTermFrequency($error);

        $idf = $this->calculateIdf()->getIdf();
        
        $vocab = $this->vectorizeError()[1];

        $temp = $this->vectorizeTf($vocab, $tf);

        $tfidf = $this->calculateTfidf($idf, $temp);
        arsort($tfidf);
        $newTfidf = array_slice($tfidf, 0, 5, true);
        
        $wordCloudError = ErrorRepository::getErrorWordCloud();

        $hold = [];

        foreach($vocab as $key => $value)
        {
            foreach($tfidf as $k => $val)
            {
                if($k == $key)
                {
                    $hold[$value] = $val;
                }
            }
        }
        
        $inputTags = [];
        foreach($wordCloudError as $key => $value)
        {
            foreach($hold as $kh => $hv)
            {
                if(in_array($kh, $value))
                {
                    array_push($inputTags, $key);
                }
            }
        }
        $inputTags = array_unique($inputTags);
        sort($inputTags);

        $corpusArr = $this->vectorizeCorpus();
        
        //$convert = $this->convertCorpusToTags($corpusArr);
        $tags = Dataset::pluck('error_type')->toArray();
        
        $dot = $this->dot($tags);

        foreach($tags as $key => $value)
        {
            $score[$key] = $this->cosine($inputTags, array($value), $dot);
        }
        
        arsort($score);
        $newScore = array_slice($score, 0, 5, true);
        dd($score);
    }

    protected function test()
    {
        $articles = array(
            array(
                "article" => "Data Mining: Finding Similar Items", 
                "tags" => array("Algorithms", "Programming", "Mining", "Python", "Ruby")
            ),
            array(
                "article" => "Blogging Platform for Hackers",  
                "tags" => array("Publishing", "Server", "Cloud", "Heroku", "Jekyll", "GAE")
            ),
            array(
                "article" => "UX Tip: Don't Hurt Me On Sign-Up", 
                "tags" => array("Web", "Design", "UX")
            ),
            array(
                "article" => "Crawling the Android Marketplace", 
                "tags" => array("Python", "Android", "Mining", "Web", "API")
            )
        );
        
        $dot = $this->dot(call_user_func_array("array_merge", array_column($articles, "tags")));
        
        $target = array('Publishing', 'Web', 'API');
        
        foreach($articles as $article) {
            $score[$article['article']] = $this->cosine($target, $article['tags'], $dot);
        }
        asort($score);
    }

    protected function convertCorpusToTags($corpusArr)
    {   
        $vocab = $corpusArr[1];
        $corpusTfidf = $corpusArr[0];

        $temp = [];

        foreach($corpusTfidf as $key => $value)
        {
            foreach($value as $k => $val)
            {
                foreach($vocab as $vocKey => $vocVal)
                {
                    if($k == $vocKey)
                    {
                        array_push($temp, $vocVal); 
                    }
                }
            }
        }

        return $temp;
    }

    protected function vectorizeCorpus()
    {
        $dataset = Dataset::pluck('data','id')->toArray();
      
        $corpusArr = [];
        $vocab = [];

        foreach($dataset as $key => $value)
        {
            $tf = $this->calculateTermFrequency($value);
            
            $idf = $this->calculateCorpusIdf($key)->getIdf();

            $vocab = $this->vectorizeCorpusError($key)[1];
            
            $temp = $this->vectorizeTf($vocab, $tf);
            
            $tfidf = $this->calculateTfidf($idf, $temp);
            arsort($tfidf);
            $newTfidf = array_slice($tfidf, 0, 5, true);

            array_push($corpusArr, $newTfidf);
        }
        
        return [$corpusArr, $vocab];
    }

    protected function calculateCorpusIdf($id)
    {
        $samples = $this->vectorizeCorpusError($id)[0];
        $transformer = new TfIdfTransformer($samples);
        $transformer->transform($samples);

        return $transformer;
    }

    protected function vectorizeCorpusError($id)
    {
        $samples = Dataset::pluck('data')->where('id', '!=', $id)->toArray();
        $vectorizer = new TokenCountVectorizer(new WordTokenizer(), new English());
        
        // Build the dictionary.
        $vectorizer->fit($samples);
        // Transform the provided text samples into a vectorized list.
        $vectorizer->transform($samples); 

        return [
            $samples,
            $vectorizer->getVocabulary()
        ];
    }

    protected function vectorizeTf($vocab, $tf)
    {
        $temp = [];

        foreach($vocab as $key => $value)
        {
            foreach($tf as $k => $val)
            {
                if($k == $value)
                {
                    $temp[$key] = $val;
                }
            }
        }

        return $temp;
    }

    protected function calculateTfidf($idf, $temp)
    {
        $tfidf = [];

        foreach($idf as $key => $value)
        {
            foreach($temp as $k => $val)
            {
                if($key == $k)
                {
                    $tfidf[$k] = $val * $value;
                } 
            }
        }
        
        return $tfidf;
    }

    protected function calculateTermFrequency($text)
    {
        $text = remove_stop_words($text, 'en');
        // Use a regular expression to split the text into an array of words
        preg_match_all('/\b\w+\b/', $text, $matches);
        $words = $matches[0];
    
        // Count the occurrences of each word
        $wordCount = array_count_values($words);

        // Calculate the Term Frequency
        $termFrequency = [];
        $totalWords = count($words);

        foreach ($wordCount as $word => $count) {
            $termFrequency[$word] = $count / $totalWords;
        }

        return $termFrequency;
    }
    
    protected function vectorizeError()
    {
        $samples = Dataset::pluck('data')->toArray();
        $vectorizer = new TokenCountVectorizer(new WordTokenizer(), new English());
        
        // Build the dictionary.
        $vectorizer->fit($samples);
        // Transform the provided text samples into a vectorized list.
        $vectorizer->transform($samples); 

        return [
            $samples,
            $vectorizer->getVocabulary()
        ];
    }

    protected function calculateIdf()
    {
        $samples = $this->vectorizeError()[0];
        $transformer = new TfIdfTransformer($samples);
        $transformer->transform($samples);

        return $transformer;
    }

    protected function dot($tags) {
		$tags = array_unique($tags);
		$tags = array_fill_keys($tags, 0);
		ksort($tags);
		return $tags;
	}
	protected function dot_product($a, $b) {
		$products = array_map(function($a, $b) {
			return $a * $b;
		}, $a, $b);
		return array_reduce($products, function($a, $b) {
			return $a + $b;
		});
	}
	protected function magnitude($point) {
		$squares = array_map(function($x) {
			return pow($x, 2);
		}, $point);
		return sqrt(array_reduce($squares, function($a, $b) {
			return $a + $b;
		}));
	}
	protected function cosine($a, $b, $base) {
        $a = array_fill_keys($a, 1) + $base;
        $b = array_fill_keys($b, 1) + $base;
		ksort($a);
		ksort($b);
		return self::dot_product($a, $b) / (self::magnitude($a) * self::magnitude($b)); 
	}
    
}
