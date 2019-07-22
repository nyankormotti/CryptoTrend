<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;

class TweetCountController extends Controller
{
    public function index(){



        $api_key = env('TWITTER_CLIENT_KEY');    // APIキー
        $api_secret = env('TWITTER_CLIENT_SECRET'); // APIシークレット


        $connection = new TwitterOAuth($api_key, $api_secret);

        $date = substr(date("Y-m-d_H:i:s", strtotime('-1 hour', time())), 0, 13);
        $since_time = $date . ":00:00_JST";
        $until_time = $date. ":59:59_JST";

        // 銘柄一覧(検索パラメータ)
        $currency = array(
            '0' => '$BTC',
            '1' => '$ETH',
            '2' => '$ETC',
            '3' => '$LSK',
            '4' => '$FCT',
            '5' => '$XRP',
            '6' => '$XEM',
            '7' => '$LTC',
            '8' => '$BCH',
            '9' => '$MONA',
        );

        

        // リクエスト回数
        $request_number = 100;

        // 検索ツイート結果
        $tweet_texts = array();
        // 検索ツイート数
        $tweet_count = array();


        // 銘柄ごとのツイート数を検索する
        for($i = 0; $i < count($currency); $i++){

            // パラメータ設定
            $params = array(
                'q'     => $currency[$i],
                'count' =>  100,
                "result_type" => "recent",
                "since" => $since_time,
                "until" => $until_time,
            );

            // パラメータ設定にて設定した銘柄のツイート数を取得
            for ($j = 0; $j < $request_number; $j++) {

                // ツイート検索実行
                $tweets_obj = $connection->get('search/tweets', $params);

                // オブジェクトを配列に変換
                $tweets_arr = json_decode(json_encode($tweets_obj), true);

                // var_dump($tweets_arr);exit;

                // ツイート本文を抽出
                for ($k = 0; $k < count($tweets_arr['statuses']); $k++) {
                    $tweet_texts[] = $tweets_arr['statuses'][$k]['text'];
                }

                // next_results が無ければ処理を終了
                if (empty($tweets_arr['search_metadata']['next_results'])) {
                    // var_dump($j);
                    break;
                }

                // 先頭の「?」を除去
                $next_results = preg_replace('/^\?/', '', $tweets_arr['search_metadata']['next_results']);

                // パラメータに変換
                parse_str($next_results, $params);
            }

            // ツイート数を格納
            // $tweet_count[$i] = count($tweet_texts);
            array_push($tweet_count, count($tweet_texts));

            // 検索ツイート結果を初期化
            $tweet_texts = array();
        }

        // var_dump($tweet_count);exit;
        return view('tweetCount',compact('tweet_count'));
    }

}


// $request_url = 'https://api.twitter.com/1.1/search/tweets.json'; // エンドポイント

        // // クレデンシャルを作成
        // $credential = base64_encode($api_key . ":" . $api_secret);

        // // リクエストURL
        // $request_url = "https://api.twitter.com/oauth2/token";

        // // リクエスト用のコンテキストを作成する
        // $context = array(
        //     "http" => array(
        //         "method" => "POST", // リクエストメソッド
        //         "header" => array(              // ヘッダー
        //             "Authorization: Basic " . $credential,
        //             "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
        //         ),
        //         "content" => http_build_query(    // ボディ
        //             array(
        //                 "grant_type" => "client_credentials",
        //             )
        //         ),
        //     ),
        // );

        // // cURLを使ってリクエスト
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $request_url);    // リクエストURL
        // curl_setopt($curl, CURLOPT_HEADER, true);    // ヘッダーを取得する 
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $context["http"]["method"]);    // メソッド
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    // 証明書の検証を行わない
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);    // curl_execの結果を文字列で返す
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $context["http"]["header"]);    // ヘッダー
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $context["http"]["content"]);    // リクエストボディ
        // curl_setopt($curl, CURLOPT_TIMEOUT, 5);    // タイムアウトの秒数
        // $res1 = curl_exec($curl);
        // $res2 = curl_getinfo($curl);
        // curl_close($curl);

        // // 取得したデータ
        // $response = substr($res1, $res2["header_size"]);	// 取得したデータ(JSONなど)

        // // jsonコードを連想配列に変換
        // $arr = json_decode($response);
        // // ベアラートークンの取得
        // $bearer_token = $arr->access_token;



        // // ===============================
        // // ツイート数取得処理
        // // ===============================
        // $request_url = 'https://api.twitter.com/1.1/search/tweets.json';        // エンドポイント

        // // 現在時刻
        // // $date = substr(date("Y-m-d_H:i:s"), 0, 13);
        // $date = date("Y-m-d_H:i:s", strtotime('-1 hour', time()));
        // $date = substr(date("Y-m-d_H:i:s", strtotime('-1 hour', time())), 0, 13);
        // // var_dump($date);exit;
        

        // $since_time = $date. ":00:00_JST";
        // $until_time = $date. ":59:59_JST";

        // var_dump($since_time);
        // var_dump($until_time);

        // // パラメータ (オプション)
        // $params = array(
        //     "q" => "BTC",
        //     //		"geocode" => "35.794507,139.790788,1km",
        //     		// "lang" => "ja",
        //     //		"locale" => "ja",
        //     		// "result_type" => "recent",
        //     		"count" => "100",
        //     		// "since" => $since_time,
        //     		// "until" => $until_time,
        //     //		"since_id" => "643299864344788992",
        //     //		"max_id" => "643299864344788992",
        //     //		"include_entities" => "true",
        // );

        // // パラメータがある場合
        // if ($params) {
        //     $request_url .= '?' . http_build_query($params);
        // }

        // // リクエスト用のコンテキスト
        // $context = array(
        //     'http' => array(
        //         'method' => 'GET', // リクエストメソッド
        //         'header' => array(              // ヘッダー
        //             'Authorization: Bearer ' . $bearer_token,
        //         ),
        //     ),
        // );

        // // cURLを使ってリクエスト
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $request_url);
        // curl_setopt($curl, CURLOPT_HEADER, 1);
        // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $context['http']['method']);            // メソッド
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);                                // 証明書の検証を行わない
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                // curl_execの結果を文字列で返す
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $context['http']['header']);            // ヘッダー
        // curl_setopt($curl, CURLOPT_TIMEOUT, 5);                                        // タイムアウトの秒数
        // $res1 = curl_exec($curl);
        // $res2 = curl_getinfo($curl);
        // curl_close($curl);

        // // 取得したデータ
        // $json = substr($res1, $res2['header_size']);                // 取得したデータ(JSONなど)
        // // $header = substr($res1, 0, $res2['header_size']);        // レスポンスヘッダー (検証に利用したい場合にどうぞ)

        // // JSONをオブジェクトに変換 (処理をする場合)
        // $obj = json_decode($json,true);


        // $tweet_texts = array();
        // var_dump($obj);exit;
        // // var_dump($obj['statuses']);
        // // var_dump(count($obj['statuses']));
        // for ($j = 0; $j < count($obj['statuses']); $j++) {
        //     $tweet_texts[] = $obj['statuses'][$j]['text'];
        //     // var_dump($tweet_texts[$j]);
        //     var_dump($obj['statuses'][$j]['created_at']);
        // }
        // // var_dump(count($tweet_texts));
        // // exit;

        // // var_dump(count($tweet_texts)); exit;
        // // var_dump(count($obj['statuses']));
        // // var_dump($obj['statuses']);exit;
