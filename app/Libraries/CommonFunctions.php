<?php

namespace App\Libraries;

use App\User;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;

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
            // ニュースタイトル
            $list[$i]['title'] = mb_convert_encoding($data[$i]->title, "UTF-8", "auto");
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