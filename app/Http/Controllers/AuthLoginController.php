<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;

class AuthLoginController extends Controller

{

    /**
     * ログイン画面表示
     * @return void
     */
    public function index()
    {
        return view('authLogin');
    }

    /**
     * ログイン処理
     * @param Request $request リクエスト
     * @return void
     */
    public function authLogin(LoginRequest $request) {

        // ログイン保持情報
        $rem = $request->pass_save;

        // 認証
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $rem)) {
            $user = User::where('id', Auth::id())->first();

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

            // sessonにアクセストークンを格納
            session()->put('oauth_token', $oauth_token);
            session()->put('oauth_token_secret', $oauth_token_secret);

            if($user->twitter_id !== $userInfo['id_str'] || $user->screen_name !== $userInfo['screen_name']) {
                return redirect()->action('ChangeTwitterAccountController@index');
            } else {
                return redirect('trend');
            }

        } else {
            $msg = 'メールアドレスまたはパスワードが違います。';
            return view('authLogin', ['message' => $msg]);
        }

    }
}
