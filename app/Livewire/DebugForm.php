<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dataset;
use App\Models\Solution;
use Illuminate\Http\Request;
use Yooper\TextAnalysis\TfIdf;
use Illuminate\Support\Facades\App;
use App\Repositories\ErrorRepository;
use Phpml\Tokenization\WordTokenizer;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\StopWordsFilter;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\StopWords\English;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use function Rap2hpoutre\RemoveStopWords\remove_stop_words;

class DebugForm extends Component
{
    public $debug;
    public $answer;
    public $hold = [];
    public $countAns = 0;

    public function process()
    {  
        $error = $this->debug;

        $dataset = Dataset::pluck('data')->toArray();
    
        array_push($dataset, $error);

        $datasetFilter = [];
        foreach($dataset as $data)
        {
            $datasetFilter[] = remove_stop_words($data);
        }
        
        $corpusTFArray =[];
        foreach($datasetFilter as $key => $data)
        {
            $corpusTFArray[$key] = $this->calculateTermFrequency($data);     
        }
 
        $corpusIDFArray = $this->calculateIdf($corpusTFArray);

        $corpusTFIDFArray = [];
        foreach($corpusTFArray as $key => $data)
        {
            $corpusTFIDFArray[$key] = $this->calculateTfidf($data, $corpusIDFArray);
        }
  
        $target = array_pop($corpusTFIDFArray);
        
        $matrix_score = [];
        $keyID = 1;
        foreach($corpusTFIDFArray as $key => $subArray)
        {
            $docCompare = $this->compareAndFillArrays($target, $subArray);
            $matrix_score[$keyID] = $this->cosine($docCompare[0], $docCompare[1]);
            $keyID++;
        }
        arsort($matrix_score);
 
        $resultArray = [];
        foreach ($matrix_score as $key => $value) 
        {
            if ($value >= 0.7 && $value <= 1) 
            {
                $resultArray[$key] = $value;
            }
        }

        $mix = [];
        foreach($resultArray as $key => $data)
        {
            $mix[] = Dataset::query()->where('id', $key)->pluck('error_type')->toArray();
        }
        
        $sol = [];
        foreach($mix as $key => $data)
        {
            foreach($data as $ky => $val)
            {
                $sol[$key] = explode(',',$val);
            }
        }
        
        $mergedArray = [];

        foreach ($sol as $subArray) 
        {
            $mergedArray = array_merge($mergedArray, $subArray);
        }
        
        $mergedArray = array_count_values($mergedArray);

        arsort($mergedArray);
        $newScore = array_slice($mergedArray, 0, 5, true);

        $id = [];
        foreach($newScore as $key => $score)
        {
            $id[] = $key;
        }

        $datasetId = Solution::whereIn('error_type', $id)->pluck('solution')->toArray();
 
        $datasetSolution = [];
        foreach($datasetId as $key => $val)
        {
            foreach($id as $ky => $value)
            {
                if(str_contains($val, $value))
                {
                    $datasetSolution[$ky] = $val;
                }
            }
        }
        ksort($datasetSolution);
        $this->hold = $datasetSolution;

        return view('debug.index');
    }

    public function increment()
    {
        $this->countAns++;

        if($this->countAns == count($this->hold))
        {
            $this->countAns = 0;
        }
        
    }

    public function calculateTermFrequency($text)
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

    public function calculateIdf($corpusTFArray)
    {
        $idf = [];
        $merge = [];
        foreach ($corpusTFArray as $subArray) {
            $merge = array_merge($merge, $subArray);
        }

        $merge = array_unique($merge);
        $count = 0;
        $totalDoc = count($corpusTFArray);
        $merge = array_keys($merge);
        $merge = array_map('strval', $merge);

        foreach($merge as $key => $text)
        {
            foreach($corpusTFArray as $subArray)
            {
                $res = array_keys($subArray);
                $res= array_map('strval', $res);

                if(in_array($text, $res))
                {
                    $count++;
                }
            }
            $idf[$text] = log($totalDoc/($count+1));
            $count = 0;
        }

        return $idf;
    }

    public function calculateTfidf($corpusTFArray, $corpusIDFArray)
    {
        $tfidf = [];
        
        foreach($corpusTFArray as $key => $value)
        {  
            if(array_key_exists($key, $corpusIDFArray))
            {
                $tfidf[$key] = $value * $corpusIDFArray[$key];
            }
        }

        return $tfidf;
    }

    public function compareAndFillArrays(array $a, array $b): array
    {
        // Find the union of keys from both arrays
        $unionKeys = array_merge(array_keys($a), array_keys($b));

        // Fill arrays with values, assigning 0 to missing keys
        $filledA = array_fill_keys($unionKeys, 0);
        $filledB = array_fill_keys($unionKeys, 0);

        foreach ($a as $key => $value) {
            $filledA[$key] = $value;
        }

        foreach ($b as $key => $value) {
            $filledB[$key] = $value;
        }

        return [$filledA, $filledB];
    }

    //Cosine Similarity Algorithm
	public function dot_product($a, $b) 
    {
        $products = array_map(function ($a, $b) {
            return $a * $b;
        }, $a, $b);
    
        return array_sum($products);
    }
	public function magnitude($vector) 
    {
        $squares = array_map(function ($x) {
            return pow($x, 2);
        }, $vector);
    
        return sqrt(array_sum($squares));
    }
	public function cosine($a, $b) 
    {
        $dotProduct = $this->dot_product($a, $b);
        $magnitudeA = $this->magnitude($a);
        $magnitudeB = $this->magnitude($b);
    
        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0; // Avoid division by zero
        }
    
        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    public function render()
    {
        if(!is_null($this->hold) && !empty($this->hold))
        {
            $this->answer = $this->hold[$this->countAns];
        }

        return view('livewire.debug-form');
    }
}
