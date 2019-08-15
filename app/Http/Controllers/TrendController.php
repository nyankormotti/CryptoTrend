<?php

namespace App\Http\Controllers;

use App\Trend;
use Illuminate\Http\Request;

class TrendController extends Controller
{

    /**
     * トレンド画面表示
     * @return void
     */
    public function index(Request $request) {
        $status = $request->session()->get('status');
        return view('trend', ['status' => $status]);
    }

    /**
     * トレンド一覧取得
     * @param Request $request (twitter_id)
     * @return array $trends(トレンド一覧)
     */
    public function getTrend(Request $request)
    {
        $trends = Trend::where('period_id',$request->period_id)
                        ->with('currency')
                        ->orderBy('tweet_count','DESC')
                        ->get();
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
     * @param Request $request (twitter_id)
     * @return String $updated (トレンド一覧更新日)
     */
    public function getUpdated(Request $request)
    {
        $trends = Trend::where('period_id', $request->period_id)
                    ->with('currency')
                    ->orderBy('tweet_count', 'DESC')
                    ->get();
        $updated = $trends[0]['updated_at'];

        return $updated;
    }
}
