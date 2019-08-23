<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Libraries\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter認証コントローラ
 */
class OAuthController extends Controller
{
    /**
     * TwitterAPI認証画面表示
     * @return void
     */
    public function login()
    {
        //インスタンス生成
        $twitter = new TwitterOAuth(env('TWITTER_CLIENT_KEY'), env('TWITTER_CLIENT_SECRET'));
        //リクエストトークン取得
        //'oauth/request_token'はリクエストークンを取得するためのAPIのリソース
        $request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => env('TWITTER_CLIENT_CALLBACK')));
        //認証用URL取得
        //'oauth/authorize'は認証URLを取得するためのAPIのリソース
        $url = $twitter->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        //TwitterAPI認証ページにリダイレクト
        return redirect($url);
    }

    /**
     * callback処理
     * ユーザーのTwitterアカウントのアクセストークンを取得
     * Twitterアカウント変更画面からの遷移の場合、TwitterAPI連携後のアカウント変更処理にリダイレクト
     * 上記以外の場合、mainメソッドの処理にリダイレクト
     * @return void
     */
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

        //セッションにアクセストークンを登録
        session()->put('oauth_token', $accessToken['oauth_token']);
        session()->put('oauth_token_secret', $accessToken['oauth_token_secret']);

        if(!empty(session()->get('change_account_flg'))) {
            // Twitterアカウント変更画面からの遷移の場合、TwitterAPI連携後のアカウント変更処理にリダイレクト
            session()->forget('change_account_flg');
            return redirect('changeTwitterAccountMain');
            return redirect()->action('ChangeTwitterAccountController@changeAccountMain');
        } else{
            //会員登録画面からの遷移の場合、Twitter情報をusersテーブルに登録する処理を実施する。
            return redirect('main');
        }
    }


    /**
     * usersテーブルにユーザー情報を登録する
     * (会員登録画面より指定したscreen_nameのTwitterアカウント情報をusersテーブルに登録する)
     * ログイン処理を実行する
     * 登録したユーザーにてAPI連携し、仮想通貨関連アカウントをaccountsテーブルに登録する
     * @return void
     */
    public function main()
    {
        //セッションからアクセストークン取得
        $oauth_token = session()->get('oauth_token');
        $oauth_token_secret = session()->get('oauth_token_secret');
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

        // セッション情報を取得
        $screen_name = session()->get('screen_name');
        $email = session()->get('email');
        $password = session()->get('password');

        if($screen_name !== $userInfo['screen_name']){
            // screen_nameがTwitterのユーザー情報と異なる場合
            // 会員登録画面にリダイレクト
            $msg = '認証するTwitterアカウントが異なります。';
            session()->put('message', $msg);
            return redirect()->action('SignUpController@index');
        }
        // usersテーブルに会員情報を登録
        $user = new User;
        $user->screen_name = $screen_name;
        $user->email = $email;
        $user->password = $password;
        $user->twitter_id = $userInfo['id_str'];;
        $user->oauth_token = $oauth_token;
        $user->oauth_token_secret = $oauth_token_secret;
        $user->follow_request_time = new Carbon();
        $user->follow_request_count = 0;
        $user->unfollow_request_time = new Carbon();
        $user->unfollow_request_count = 0;
        $user->follow_limit = 0;
        $user->unfollow_limit = 0;
        $user->save();

        // ログイン認証
        Auth::login($user);

        // 会員登録画面にて格納したsessionのパラメータを破棄
        session()->forget('screen_name');
        session()->forget('email');
        session()->forget('password');

        // 更新したuser情報を取得
        $user = User::where('id', Auth::id())->first();

        // 登録したユーザー情報にひもづく仮想通貨関連アカウントを取得
        // 共通関数呼び出し
        $commonFunc = new CommonFunctions;
        $commonFunc->getAccount($user);

        // トレンド一覧画面へ遷移
        return redirect('trend');
    }
}
