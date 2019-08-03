<?php

namespace App\Http\Controllers;

use App\Trend;
use App\Currency;
use Illuminate\Http\Request;

class TrendController extends Controller
{
    public function index() {
        
        $trends = Trend::where('period_id','1')->orderBy('tweet_count','DESC')->get();
        // var_dump($trends[0]->currency->currency_name);exit;
        $rank = 0;
        return view('trend', ['trends' => $trends, 'rank' => $rank]);
    }
}
