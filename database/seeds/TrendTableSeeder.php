<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * trendテーブルのレコードを作成するシーダー
 * (バッチ処理では、このレコードを更新する)
 */
class TrendTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 期間id(priod_id)の数だけ繰り返し(1時間、１日、1週間あたりのツイート数のレコードの雛形を登録する。)
        for ($i = 1; $i <= 3; $i++){
            // 銘柄idの数だけ繰り返し(各銘柄のツイート数のレコードの雛形登録する。)
            for($j = 1;$j <= 10; $j++ ){
                DB::table('trends')->insert([
                    'period_id' => $i,
                    'currency_id' => $j,
                    'tweet_count' => 0,
                    'acquisition_date' => Carbon::now()
                ]);
            }

        }
    }
}
