<?php

namespace App\Http\Controllers;

use App\Trend;
use App\Currency;
use Illuminate\Http\Request;

class TrendController extends Controller
{
    public function index() {
        
        $trends = Trend::where('period_id','1')->with('currency')->orderBy('tweet_count','DESC')->get();
        // var_dump($trends);exit;
        // var_dump($trends[0]->currency->currency_name);exit;
        $rank = 0;
        foreach($trends as $trend){
            $trends[$rank]['rank'] = $rank + 1;
            $rank++;
        }
        
        return $trends;
        // return view('trend', ['trends' => $trends, 'rank' => $rank]);
    }
}
