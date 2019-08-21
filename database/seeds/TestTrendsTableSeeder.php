<?php

use App\Trend;
use App\Tweet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestTrendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nowDate = new Carbon();
        $acquisition_date = (substr($nowDate->subHour(), 0, 13)) . ":00:00";

        // Tweetsテーブルより、1時間当たりのツイート数を取得
        $tweet = Tweet::where('acquisition_date', '=', $acquisition_date)
            ->orderBy('currency_id')
            ->get();

        // Trendsテーブルに1時間あたりのツイート数を更新
        for ($i = 0; $i < 10; $i++) {
            $trend = Trend::where('period_id', '1')
                ->where('currency_id', $tweet[$i]['currency_id'])
                ->first();

            $trend->tweet_count = $tweet[$i]['tweet_count'];
            $trend->acquisition_date = $acquisition_date;
            $trend->save();
        }

        // 現在時刻より過去1日の各銘柄のツイート数を更新 処理開始
        // 現在日付よりも1日前の日時
        $one_days_ago_date = date("Y-m-d H:i:s", strtotime($acquisition_date . "-23 hour"));

        // 銘柄ごとの過去1日のツイート数を格納する配列
        $tweet_days_count = array();

        // Tweetsテーブルより、各銘柄の過去一日のツイート数を取得
        for ($i = 0; $i < 10; $i++) {
            $sum = DB::table('tweets')
                ->where('currency_id', $i + 1)
                ->where('acquisition_date', '<=', $acquisition_date)
                ->where('acquisition_date', '>=', $one_days_ago_date)
                ->sum('tweet_count');

            array_push($tweet_days_count, $sum);
        }

        // Trendテーブル(1日あたり)のデータ編集
        for ($i = 0; $i < 10; $i++) {
            $trend = Trend::where('period_id', '2')
                ->where('currency_id', $i + 1)
                ->first();

            $trend->tweet_count = $tweet_days_count[$i];
            $trend->acquisition_date = $acquisition_date;
            $trend->save();
        }
        // 現在時刻より過去1日の各銘柄のツイート数を更新 処理終了

        // 現在時刻より過去1週間の各銘柄のツイート数を更新 処理開始
        // 現在日付よりも1週間前の日時
        $one_weeks_ago_date = date("Y-m-d H:i:s", strtotime($acquisition_date . "-7 day" . "+1 hour"));

        // 銘柄ごとの過去1週間のツイート数を格納する配列
        $tweet_weeks_count = array();

        // Tweetsテーブルより、各銘柄の過去一週間のツイート数を取得
        for ($i = 0; $i < 10; $i++) {
            $sum = DB::table('tweets')
                ->where('currency_id', $i + 1)
                ->where('acquisition_date', '<=', $acquisition_date)
                ->where('acquisition_date', '>=', $one_weeks_ago_date)
                ->sum('tweet_count');

            array_push($tweet_weeks_count, $sum);
        }

        // Trendテーブル(1週間あたり)のデータ編集
        for ($i = 0; $i < 10; $i++) {
            $trend = Trend::where('period_id', '3')
                ->where('currency_id', $i + 1)
                ->first();

            $trend->tweet_count = $tweet_weeks_count[$i];
            $trend->acquisition_date = $acquisition_date;
            $trend->save();
        }
            // 現在時刻より過去1週間の各銘柄のツイート数を更新 処理終了
    }
}
