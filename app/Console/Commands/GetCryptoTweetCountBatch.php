<?php

namespace App\Console\Commands;

use App\Trend;
use App\Tweet;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * 各銘柄のツイート数集計バッチ
 * tweetsテーブルに各銘柄の1時間当たりのツイート数を集計し登録
 * trendsテーブルに各銘柄の1時間、1日、1週間当たりのツイート数をしゅうけいし登録
 */
class GetCryptoTweetCountBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:CryptoTweet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '各銘柄のツイート数を集計';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('===============');
        \Log::info('各銘柄のツイート数集計バッチ開始');
        // アプリケーション単独認証処理(ベアラートークン)
        // APIキー
        $api_key = env('TWITTER_CLIENT_KEY');
        // APIシークレット
        $api_secret = env('TWITTER_CLIENT_SECRET');
        // アプリケーション認証
        $connection = new TwitterOAuth($api_key, $api_secret);

        // 銘柄一覧(検索パラメータ)
        $currency = array(
            '0' => '$BTC OR #BTC OR Bitcoin OR ビットコイン -BitcoinCash -ビットコインキャッシュ',
            '1' => '$ETH OR #ETH OR Ethereum OR イーサリアム -EthereumClassic -イーサリアムクラシック',
            '2' => '$ETC OR EthereumClassic  OR イーサリアムクラシック',
            '3' => '$LSK OR #LSK OR $LISK OR #LISK',
            '4' => '$FCT OR #FCT OR Factom OR ファクトム',
            '5' => '$XRP OR #XRP OR Ripple OR #リップル',
            '6' => '$XEM OR #XEM OR $NEM OR #NEM OR #ネム',
            '7' => '$LTC OR #LTC OR Litecoin OR ライトコイン',
            '8' => '$BCH OR #BCH OR BitcoinCash OR ビットコインキャッシュ',
            '9' => '$MONA OR MONACOIN OR モナコイン',
        );

        // ツイート検索期間の設定(開始日時、終了日時)
        $date = substr(date("Y-m-d_H:i:s", strtotime('-1 hour', time())), 0, 13);
        // ツイート検索期間_開始日時
        $since_time = $date . ":00:00_JST";
        // ツイート検索期間_終了日時
        $until_time = $date . ":59:59_JST";
        // ツイート数取得時間(DB格納用)
        $nowDate = new Carbon();
        $acquisition_date = (substr($nowDate->subHour(), 0, 13)).":00:00";

        // 15分間のAPIリクエスト上限回数
        $RQUEST_LIMIT = 450;

        // 1銘柄あたりのリクエスト回数上限
        // (15分間の制限回数が450回なので、1時間の制限回数は計1800回)
        // (1銘柄あたり180回を制限とする)
        $REQUEST_COUNT = 180;

        // 検索ツイート結果
        $tweet_texts = array();
        // 検索ツイート数
        $tweet_count = "";

        // APIリクエスト開始時間(15分間隔のリクエストにおける開始時間)
        $start_time = new Carbon();

        // APIリクエスト開始時間(1時間(バッチ処理時間内)のリクエストにおける開始時間)
        $total_start_time = new Carbon();

        // リクエスト回数(カウント用)(15分間におけるリクエスト回数(上限は450回))
        $request_count = 0;

        // 銘柄ごとのツイート数を検索する
        for ($i = 0; $i < count($currency); $i++) {

            // 現在時刻を取得
            $total_start_time = new Carbon();

            // パラメータ設定
            $params = array(
                'q'     => $currency[$i],
                'count' =>  100,
                "result_type" => "recent",
                "since" => $since_time,
                "until" => $until_time,
            );

            // パラメータ設定にて設定した銘柄のツイート数を取得
            for ($j = 0; $j < $REQUEST_COUNT; $j++) {

                // 現在時刻を取得
                $now_time = new Carbon();

                // 最初のリクエスト開始から1時間が経過した場合
                // (次の毎時バッチが稼働するため、1時間の30秒前で処理を終了します。)
                if(3570 < $total_start_time->diffInSeconds($now_time)){
                    break;
                }
                // 初めのAPIリクエストより15分(900秒)以上経過していないしていない
                // かつリクエスト回数が上限(450回)に達した場合
                elseif(900 > $start_time->diffInSeconds($now_time) && $request_count === $RQUEST_LIMIT){
                    // 最初のリクエストから15分(900秒)経過するまで待機
                    sleep(900 - $start_time->diffInMinutes($now_time));
                    // APIリクエスト開始時間を現在時刻に上書き
                    $start_time = new Carbon();
                    // リクエスト回数を初期化
                    $request_count = 0;
                }
                // 初めのAPIリクエストより15分(900秒)以上経過した場合
                elseif(900 <= $start_time->diffInSeconds($now_time)){
                    // APIリクエスト開始時間を現在時刻に上書き
                    $start_time = new Carbon();
                    // リクエスト回数を初期化
                    $request_count = 0;
                }

                // ツイート検索実行 (APIリクエスト実行)
                $tweets_obj = $connection->get('search/tweets', $params);

                // リクエスト回数 カウントアップ
                $request_count = $request_count + 1;

                // オブジェクトを配列に変換
                $tweets_arr = json_decode(json_encode($tweets_obj), true);

                // Tweet数が取得できない場合,(statusesがない場合)、処理を終了
                if (empty($tweets_arr['statuses'])) {
                    break;
                }

                // ツイート本文を抽出
                for ($k = 0; $k < count($tweets_arr['statuses']); $k++) {
                    $tweet_texts[] = $tweets_arr['statuses'][$k]['text'];
                }

                // next_results が無ければ処理を終了(次のツイートがない場合)
                if (empty($tweets_arr['search_metadata']['next_results'])) {
                    break;
                }

                // 先頭の「?」を除去
                $next_results = preg_replace('/^\?/', '', $tweets_arr['search_metadata']['next_results']);
                // パラメータに変換
                parse_str($next_results, $params);
            }

            // 現在時刻を取得
            $now_time = new Carbon();

            // 最初のリクエスト開始から1時間が経過した場合
            // (次の毎時バッチが稼働するため、1時間の30秒前で処理を終了します。)
            if (3570 < $total_start_time->diffInSeconds($now_time)) {
                // \Log::info('銘柄取得後、1時間を超えたので、break');
                break;
            }

            // ツイート数を格納
            $tweet_count = count($tweet_texts);

            // DBへツイート数を格納
            $tweet = new Tweet();
            $tweet->currency_id = $i + 1;
            $tweet->tweet_count = $tweet_count;
            $tweet->acquisition_date = $acquisition_date;
            $tweet->save();

            // 検索ツイート結果を初期化
            $tweet_texts = array();
        }

        DB::beginTransaction();
        try {
            // Trendsテーブルを更新
            // 1時間当たりの各銘柄のツイート数を更新 処理開始

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
            // 1時間当たりの各銘柄のツイート数を更新 処理終了

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

            // 現在時間より1週間前より以前のツイート数のレコードを削除(Tweetsテーブルより削除)
            $delete_weeks_ago_date = date("Y-m-d H:i:s", strtotime($acquisition_date . "-7 day"));
            $tweet = DB::table('tweets')
                ->where('acquisition_date', '<=', $delete_weeks_ago_date)
                ->delete();
            // 削除処理終了
            DB::commit();

        } catch(Exception $e) {
            DB::rollback();
        }

        \Log::info('各銘柄のツイート数集計バッチ終了');
        \Log::info('===============');

    }
}
