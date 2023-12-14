<?php

namespace App\Http\Controllers;

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
        $error = $request->input('message');

        $tf = $this->calculateTermFrequency($error);

        $idf = $this->calculateIdf()->getIdf();

        $vocab = $this->vectorizeError()[1];

        $temp = $this->vectorizeTf($vocab, $tf);

        $tfidf = $this->calculateTfidf($idf, $temp);

        dd($tfidf, $temp, $vocab);
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

    
}
