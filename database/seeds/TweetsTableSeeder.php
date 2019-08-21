<?php


use App\Tweet;
use Carbon\Carbon;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Database\Seeder;

class TweetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        // ツイート数取得時間(DB格納用)
        $date = new Carbon();
        $acquisition_date = (substr($date->subDay(7), 0, 13)) . ":00:00";

        $createDate = date("Y-m-d_H:i:s", strtotime('-1 week', time()));
        $periodDate = substr($createDate, 0, 13);
        // ツイート検索期間_開始日時
        $since_time = (substr($periodDate, 0, 13))  . ":00:00_JST";
        // ツイート検索期間_終了日時
        $until_time = (substr($periodDate, 0, 13)) . ":59:59_JST";

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

        for ($h = 0; $h < 168; $h++) {
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
                    if (3570 < $total_start_time->diffInSeconds($now_time)) {
                        break;
                    }
                    // 初めのAPIリクエストより15分(900秒)以上経過していないしていない
                    // かつリクエスト回数が上限(450回)に達した場合
                    elseif (900 > $start_time->diffInSeconds($now_time) && $request_count === $RQUEST_LIMIT) {
                        // 最初のリクエストから15分(900秒)経過するまで待機
                        sleep(900 - $start_time->diffInMinutes($now_time));
                        // APIリクエスト開始時間を現在時刻に上書き
                        $start_time = new Carbon();
                        // リクエスト回数を初期化
                        $request_count = 0;
                    }
                    // 初めのAPIリクエストより15分(900秒)以上経過した場合
                    elseif (900 <= $start_time->diffInSeconds($now_time)) {
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

                    if(empty($tweets_arr['statuses'])) {
                        break;
                    }
                    // ツイート本文を抽出
                    for ($k = 0; $k < count($tweets_arr['statuses']); $k++) {
                        $tweet_texts[] = $tweets_arr['statuses'][$k]['text'];
                    }

                    // next_results が無ければ処理を終了
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

            $acquisition_date = (substr($date->addHour(), 0, 13)) . ":00:00";
            $createDate = date("Y-m-d_H:i:s", strtotime('-1 week +'.$h.' hour', time()));
            $periodDate = substr($createDate, 0, 13);
            // ツイート検索期間_開始日時
            $since_time = (substr($periodDate, 0, 13))  . ":00:00_JST";
            // ツイート検索期間_終了日時
            $until_time = (substr($periodDate, 0, 13)) . ":59:59_JST";

            \Log::info('回数'. $h);
            \Log::info('開始時間'. $since_time);
            \Log::info('終了時間'. $until_time);
        }
    }
}
