<?php

namespace App\Http\Controllers;

use App\Trend;
use App\Currency;
use Illuminate\Http\Request;

class TrendController extends Controller
{

    public function index() {
        return view('trend');
    }

    /**
     * トレンド一覧取得
     * @param $request (twitter_id)
     * @return $trends(トレンド一覧)
     */
    public function getTrend(Request $request)
    {
        $trends = Trend::where('period_id',$request->period_id)->with('currency')->orderBy('tweet_count','DESC')->get();
        $rank = 0;
        // ランキング付け処理
        foreach($trends as $trend){
            $trends[$rank]['rank'] = $rank + 1;
            $rank++;
        }
        return $trends;
    }

    /**
     * トレンド一覧更新日取得
     * @param $request (twitter_id)
     * @return $updated (トレンド一覧更新日)
     */
    public function getUpdated(Request $request)
    {
        $trends = Trend::where('period_id', $request->period_id)->with('currency')->orderBy('tweet_count', 'DESC')->get();
        $updated = $trends[0]['updated_at'];

        return $updated;
    }
}
