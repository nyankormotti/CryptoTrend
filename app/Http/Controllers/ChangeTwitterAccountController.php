<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Requests\ChangeTwitterAccount;

/**
 * 仮想通貨関連アカウントコントローラ
 * 
 */
class ChangeTwitterAccountController extends Controller
{
    /**
     * Twitterアカウント変更画面表示
     * @return void
     */
    public function index()
    {
        // エラーメッセージがある場合
        if (!empty(session()->get('message'))) {
            // エラーメッセージ、Twitterアカウントのセッション情報を取得
            $msg = session()->get('message');
            $err_screen_name = session()->get('screen_name');
            // エラーメッセージ、Twitterアカウントのセッション情報を削除
            session()->forget('message');
            session()->forget('screen_name');
            return view('changeTwitterAccount', ['message' => $msg, 'err_screen_name' => $err_screen_name]);
        }
        return view('changeTwitterAccount');
    }

    /**
     * Twitterアカウント変更処理
     * @param ChangeTwitterAccount $request (screen_name)
     * @return void
     */
    public function changeAccount(ChangeTwitterAccount $request)
    {
        // セッションに格納
        session()->put('screen_name', $request->screen_name);
        session()->put('change_account_flg', true);
        // Twitter API認証処理を実施
        return redirect()->action('OAuthController@login');
    }

    /**
     * TwitterAPI連携後のアカウント変更処理 (Twitter API認証のcallback処理後に遷移してくる)
     * @return void
     */
    public function changeAccountMain() {
        // ユーザー情報を取得
        $user = User::where('id', Auth::id())->first();
        // セッションからscreen_nameを取得
        $screen_name = session()->get('screen_name');
        //セッションからアクセストークン取得
        $oauth_token = session()->get('oauth_token');
        $oauth_token_secret = session()->get('oauth_token_secret');
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
        if($screen_name !== $userInfo['screen_name']) {
            // screen_nameがTwitterのユーザー情報と異なる場合
            // Twitterアカウント変更画面にリダイレクト
            $msg = '認証するTwitterアカウントが異なります。';
            session()->put('message', $msg);
            return redirect()->action('ChangeTwitterAccountController@index');
        }
        // Usersテーブルのアカウント情報を更新する
        if ($user->twitter_id !== $userInfo['id_str']) {
            // 別のTwitterアカウントを登録する場合
            $user->screen_name = $screen_name;
            $user->twitter_id = $userInfo['id_str'];
            $user->oauth_token = $oauth_token;
            $user->oauth_token_secret = $oauth_token_secret;
            $user->request_count = 0;
            $user->follow_limit = 0;
            $user->unfollow_limit = 0;
        } else {
            // Twitterのscreen_nameのみ変更する場合
            $user->screen_name = $userInfo['screen_name'];
        }
        // Usersテーブル更新処理
        $user->save();

        return redirect()->action('TrendController@index');
    }
}
