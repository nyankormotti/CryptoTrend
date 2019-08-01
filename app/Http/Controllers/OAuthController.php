<?php

namespace App\Http\Controllers;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Abraham\TwitterOAuth\TwitterOAuth;

class OAuthController extends Controller
{

    //アクセスしてTwitterのOAuth認証のページまでリダイレクトするところまで記述する
    public function login()
    {
        //インスタンス生成
        $twitter = new TwitterOAuth(env('TWITTER_CLIENT_KEY'), env('TWITTER_CLIENT_SECRET'));

        //リクエストトークン取得
        //リクエストトークンは認証用のURLを生成するのに必要になります
        //'oauth/request_token'はリクエストークンを取得するためのAPIのリソース
        $request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => env('TWITTER_CLIENT_CALLBACK')));

        //認証用URL取得
        //'oauth/authorize'は認証URLを取得するためのAPIのリソース
        $url = $twitter->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        //認証ページにリダイレクト
        return redirect($url);
    }
    //callBack後の処理について書く(アクセストークンとか取得する)
    public function callBack()
    {
        //GETパラメータから認証トークン取得
        $oauth_token = Input::get('oauth_token');
        //GETパラメータから認証キー取得
        $oauth_verifier = Input::get('oauth_verifier');

        //インスタンス生成
        $twitter = new TwitterOAuth(
            //API Key
            env('TWITTER_CLIENT_KEY'),
            //API Secret
            env('TWITTER_CLIENT_SECRET'),
            //認証トークン
            $oauth_token,
            //認証キー
            $oauth_verifier
        );

        //アクセストークン取得
        //'oauth/access_token'はアクセストークンを取得するためのAPIのリソース
        $accessToken = $twitter->oauth('oauth/access_token', array('oauth_token' => $oauth_token, 'oauth_verifier' => $oauth_verifier));

        // var_dump($accessToken);exit;

        //セッションにアクセストークンを登録
        session()->put('oauth_token', $accessToken['oauth_token']);
        session()->put('oauth_token_secret', $accessToken['oauth_token_secret']);
        // session()->put('accessToken', $accessToken);

        //indexページにリダイレクト
        return redirect('main');
    }

    //アクセストークンを使用してAPIを叩いて結果をビューに受け渡す
    public function main()
    {
        //セッションからアクセストークン取得
        $oauth_token = session()->get('oauth_token');
        $oauth_token_secret = session()->get('oauth_token_secret');
        // $accessToken = session()->get('accessToken');

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

        //ユーザ情報を取得
        //'account/verify_credentials'はユーザ情報を取得するためのAPIのリソース
        // get_object_vars()でオブジェクトの中身をjsonで返す
        $userInfo = get_object_vars($twitter->get('account/verify_credentials'));

        $screen_name = session()->get('screen_name');
        $email = session()->get('email');
        $password = session()->get('password');
        

        if($screen_name !== $userInfo['screen_name']){
            session()->flush();
            return redirect('signup');
        }


        // usersテーブルに会員情報を登録
        $user = new User;
        $user->screen_name = $screen_name;
        $user->email = $email;
        $user->password = $password;
        $user->twitter_id = $userInfo['id_str'];;
        $user->oauth_token = $oauth_token;
        $user->oauth_token_secret = $oauth_token_secret;
        $user->follow_limit = 0;
        $user->unfollow_limit = 0;
        $user->save();

        // ログイン認証
        Auth::login($user);

        // 会員登録画面にて格納したsessionのパラメータを破棄
        session()->forget('screen_name');
        session()->forget('email');
        session()->forget('password');

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

        // ユーザーIDを取得
        $id = Auth::id();

        for ($i = 0; $i < 100; $i++) {

            // 検索パラメータ
            $params = array(
                'q'     => '仮想通貨',
                'page'  => $i + 1,
                'count' =>  20,
            );

            // twitter ユーザー認証処理(アカウント検索処理)
            $account = $twitter->get('users/search', $params);
            // オブジェクト形式を配列形式に変換
            $twitter_account = json_decode(json_encode($account), true);


            // アカウントが取得できなかった場合、ループ処理を終了させる
            if (!empty($twitter_account['errors'])) {
                break;
            }

            for ($j = 0; $j < count($twitter_account); $j++) {

                // アカウントの情報が取得できなかった場合、処理を終了させる
                if (empty($twitter_account[$j]['status']['created_at'])) {
                    break;
                }
                $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$j]['status']['created_at']));

                // 活動時間が現在日時よりも1日より過去だった場合、DBへのアカウント情報の格納処理をスキップする。
                if ($account_date < $one_month_ago) {
                    continue;
                }

                // 自分と同じアカウントはAccoutテーブルには格納しない。
                if ($screen_name == $twitter_account[$j]['screen_name']) {
                    continue;
                }

                // アカウント情報をAccountテーブルに格納
                $account = new Account();
                $account->user_id = $id;
                $account->twitter_id = $twitter_account[$j]['id_str'];
                $account->screen_name = $twitter_account[$j]['screen_name'];
                $account->account_name = $twitter_account[$j]['name'];
                $account->follow = $twitter_account[$j]['friends_count'];
                $account->follower = $twitter_account[$j]['followers_count'];
                $account->profile = $twitter_account[$j]['description'];
                $account->recent_tweet = $twitter_account[$j]['status']['text'];
                $account->follow_flg = $twitter_account[$j]['following'];
                $account->save();
            }
        }

        //twitterというビューにユーザ情報が入った$userInfoを受け渡す
        // return view('twitter', ['userInfo' => $userInfo]);
        return view('trend');
    }
    //ログアウト処理(今回は最終的にwelcomeページにリダイレクトするようにする)
    public function logout()
    {
        //セッションクリア
        // session()->flush();
        // アクセストークンだけsessionから破棄
        // session()->forget('accessToken');
        session()->forget('oauth_token');
        session()->forget('oauth_token_secret');
        // session()->flush();

        // ログアウト処理
        Auth::logout();

        //OAuthログイン画面にリダイレクト
        return redirect('/');
    }
}
