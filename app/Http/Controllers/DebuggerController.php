<?php
namespace App\Http\Controllers;

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

class DebuggerController extends Controller
{
    //
    public function index()
    {
        return view('debug.index');
    }
}
