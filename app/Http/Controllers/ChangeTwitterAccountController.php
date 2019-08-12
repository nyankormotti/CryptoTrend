<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Requests\ChangeTwitterAccount;

class ChangeTwitterAccountController extends Controller
{
    /**
     * Twitterアカウント変更画面表示
     * @return void
     */
    public function index()
    {
        return view('changeTwitterAccount');
    }

    /**
     * Twitterアカウント変更処理
     * @param Request $request リクエストパラメータ
     * @return void
     */
    public function changeAccount(ChangeTwitterAccount $request)
    {
        session()->put('screen_name', $request->screen_name);
        session()->put('change_account_flg', true);

        \Log::info('changeAccount');
        \Log::info(session()->get('screen_name'));
        \Log::info(session()->get('change_account_flg'));

        // Twitter API連携処理を実施
        return redirect()->action('OAuthController@login');

        

    }

    /**
     * TwitterAPI連携後のアカウント変更処理
     * @param Request $request リクエストパラメータ
     * @return void
     */
    public function changeAccountMain() {
        \Log::info('changeAccountMain');
        \Log::info(session()->get('screen_name'));
        $user = User::where('id', Auth::id())->first();

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

        if ($user->twitter_id !== $userInfo['id_str']) {
            $user->screen_name = $screen_name;
            $user->twitter_id = $userInfo['id_str'];;
            $user->oauth_token = $oauth_token;
            $user->oauth_token_secret = $oauth_token_secret;
            $user->request_count = 0;
            $user->follow_limit = 0;
            $user->unfollow_limit = 0;
        } else {
            $user->screen_name = $userInfo['screen_name'];
        }
        $user->save();

        return redirect()->action('TrendController@index');
    }
}
