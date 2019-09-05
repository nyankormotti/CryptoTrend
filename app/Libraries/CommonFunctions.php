<?php

namespace App\Libraries;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * 共通関数
 */
class CommonFunctions
{
    /**
     * 仮想通貨関連ニュース 取得関数
     * @return array $list 仮想通貨関連ニュース一覧
     */
    public function getNews()
    {
        set_time_limit(90);
        // ニュース一覧の配列
        $list = array();
        //キーワード検索したいときのベースURL 
        $API_BASE_URL = "https://news.google.com/news?hl=ja&ned=ja&ie=UTF-8&oe=UTF-8&output=atom&q=";
        //キーワードの文字コード変更
        // 「仮想通貨」のキーワードを指定
        $query = urlencode(mb_convert_encoding("仮想通貨", "UTF-8", "auto"));
        //APIへのリクエストURL生成
        $api_url = $API_BASE_URL . $query;
        //APIにアクセス、結果をsimplexmlに格納
        $contents = file_get_contents($api_url);
        $xml = simplexml_load_string($contents);
        //記事エントリを取り出す
        $data = $xml->entry;
        //記事のタイトルとURLを取り出して配列に格納
        for ($i = 0; $i < count($data); $i++) {
            $total_title = mb_convert_encoding($data[$i]->title, "UTF-8", "auto");
            // ニュースタイトル
            $list[$i]['title'] = substr($total_title, 0, strpos($total_title, '-', 0));
            // メディア
            $list[$i]['media'] = substr($total_title, strpos($total_title, '-', 0) + 1, strlen($total_title) - 1);
            // 更新日時
            $list[$i]['updated'] = mb_convert_encoding($data[$i]->updated, "UTF-8", "auto");
            $list[$i]['updated'] = substr($list[$i]['updated'],0,10).' '. substr($list[$i]['updated'], 11, 8);
            // 記事URL
            $url_split =  explode("=", (string) $data[$i]->link->attributes()->href);
            $list[$i]['url'] = end($url_split);
        }


        return $list;
    }

    /**
     * 仮想通貨取引価格 取得関数
     * @return array $rate 仮想通貨取引価格一覧
     */
    public function coincheck()
    {
        // coincheck ticker API連携
        $strUrl = "https://coincheck.com/api/ticker";
        $file = file_get_contents($strUrl);
        $file = mb_convert_encoding($file, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        // jsonの文字列をデコードする
        $rate = json_decode($file, true);
        return $rate;
    }


    /**
     * 仮想通貨関連アカウント取得処理
     * @param array User $user
     * @return void
     */
     public function getAccount($user) 
     {
        // ユーザー情報よりアクセストークンを取得
        $oauth_token = $user->oauth_token;
        $oauth_token_secret = $user->oauth_token_secret;
        // Accountテーブルに「仮想通貨」キーワードで検索したtwitterアカウントを登録する。
        // ユーザーごとに他のアカウント情報を保持する(フォローの有無があるため)
        //インスタンス生成
        $twitter = new TwitterOAuth(
            //API Key
            env('TWITTER_CLIENT_KEY'),
            //API Secret
            env('TWITTER_CLIENT_SECRET'),
            //アクセストークン
            $oauth_token,
            $oauth_token_secret
        );

        // 現在日付より1ヶ月前の日時を取得(1ヶ月以内に活動しているアカウントを選定するため)
        $one_month_ago = new Carbon();
        $one_month_ago->subMonth();
        // APIフラグ(1ユーザーごとのAPI連携のフラグ、処理が終了したらfalseにする。)
        $api_flg = true;
        // 15分間のAPIリクエスト上限回数('users/search'のリクエスト制限は1ユーザー15分間で900回まで)
        $RQUEST_LIMIT = 900;
        // リクエスト回数(カウント用)(15分間におけるリクエスト回数(上限は900回))
        $request_count = 0;
        // ページカウント
        $page_count = 0;
        // 初回API連携の時間を取得
        $start_time = new Carbon();

        // Twitter API連携処理
        while ($api_flg) {
            // ページカウントのカウントアップ
            $page_count = $page_count + 1;

            // 検索パラメータ
            $params = array(
                'q'     => '仮想通貨',
                'page'  => $page_count,
                'count' =>  20,
            );
            // 現在時刻を取得
            $now_time = new Carbon();
            // 初めのAPIリクエストより15分以上経過していないしていない
            // かつリクエスト回数が上限(900回)に達した場合
            if (900 > $start_time->diffInSeconds($now_time) && $request_count === $RQUEST_LIMIT) {
                // 最初のリクエストから15分経過するまで待機
                sleep(900 - $start_time->diffInMinutes($now_time));
                // APIリクエスト開始時間を現在時刻に上書き
                $start_time = new Carbon();
                // リクエスト回数を初期化
                $request_count = 0;
            }
            // 初めのAPIリクエストより15分以上経過した場合
            elseif (900 <= $start_time->diffInSeconds($now_time)) {
                // APIリクエスト開始時間を現在時刻に上書き
                $start_time = new Carbon();
                // リクエスト回数を初期化
                $request_count = 0;
            }

            // TwitterAPI連携実行(関連仮想通貨アカウント取得)
            $account = $twitter->get('users/search', $params);
            // オブジェクト形式を配列形式に変換
            $twitter_account = json_decode(json_encode($account), true);
            // リクエスト回数 カウントアップ
            $request_count = $request_count + 1;
            // アカウントが取得できなかった場合、ループ処理を終了させる
            if (!empty($twitter_account['errors'])) {
                break;
            }
            // 取得したアカウント情報をaccountsテーブルに登録する
            for ($i = 0; $i < count($twitter_account); $i++) {
                // アカウントの情報が取得できなかった場合、処理を終了させる
                if (empty($twitter_account[$i]['status']['created_at'])) {
                    break;
                }
                // Twitterアカウントの最終更新日時を取得
                $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$i]['status']['created_at']));
                // 活動時間が現在日時よりも1ヶ月より過去だった場合、DBへのアカウント情報の格納処理をスキップする。
                if ($account_date < $one_month_ago) {
                    continue;
                }
                // 自分と同じアカウントはAccountテーブルには格納しない。
                if ($user->screen_name == $twitter_account[$i]['screen_name']) {
                    continue;
                }
                // アカウント情報をAccountテーブルに格納
                $account = new Account();
                $account->user_id = $user->id;
                $account->twitter_id = $twitter_account[$i]['id_str'];
                $account->screen_name = $twitter_account[$i]['screen_name'];
                $account->account_name = $twitter_account[$i]['name'];
                $account->follow = $twitter_account[$i]['friends_count'];
                $account->follower = $twitter_account[$i]['followers_count'];
                $account->profile = $twitter_account[$i]['description'];
                $account->recent_tweet = $twitter_account[$i]['status']['text'];
                $account->last_updated = $account_date;
                $account->follow_flg = $twitter_account[$i]['following'];
                $account->save();
            }
        }
     }

    /**
     * Twitterアカウント照合処理
     * @return boolean $change_flg Twitterアカウント変更有無フラグ
     */
    public function checkAccount()
    {
        // Twitterアカウント変更有無フラグ
        $change_flg = false;
        // ユーザー情報を取得
        $user = User::where('id', Auth::id())->first();

        $screen_name = $user->screen_name;
        $oauth_token = $user->oauth_token;
        $oauth_token_secret = $user->oauth_token_secret;
        // twitter認証
        //インスタンス生成
        $twitter = new TwitterOAuth(
            //API Key
            env('TWITTER_CLIENT_KEY'),
            //API Secret
            env('TWITTER_CLIENT_SECRET'),
            //アクセストークン
            $oauth_token,
            $oauth_token_secret
        );

        // twitterのユーザー情報を取得
        $userInfo = get_object_vars($twitter->get('account/verify_credentials'));

        if ($screen_name !== $userInfo['screen_name']) {
            // Twitterアカウントのスクリーンネームが、CryptoTrendに保持しているユーザー情報のものと異なる場合、
            // Twitterアカウント変更画面にリダイレクト
            $change_flg = true;
        }

        return $change_flg;
    }
}