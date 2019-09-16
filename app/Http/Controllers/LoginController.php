<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * ログインコントローラ
 */
class LoginController extends Controller
{
    /**
     * ログイン画面表示
     * @return void
     */
    public function index()
    {
        return view('login');
    }

    /**
     * ログイン処理
     * 入力値をusersテーブルの値と照合
     * usersテーブルのメールアドレスとパスワードの称号が問題なければ、usersテーブルに登録されたアクセストークンを用いて、
     * Twitterのアカウント情報を取得し、usersテーブルに登録したscreen_nameと照合する。
     * 同じであれば、トレンド一覧画面へ遷移、異なれば、Twitterアカウント変更画面へ遷移する。
     * @param Request $request (email, password, pass_save)
     * @return void
     */
    public function login(LoginRequest $request) {

        // ログイン保持情報
        $rem = $request->pass_save;

        // 認証
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'delete_flg' => false], $rem)) {
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

            // Twitter上のアカウントと比較して、DBに保存しているアカウント情報が異なる場合、Twitterアカウント変更画面にリダイレクト
            // (アカウントのTwitter_idが異なる場合(アカウントが存在しない場合)またはscreen_nameが異なる場合)
            if($user->twitter_id !== $userInfo['id_str'] || $user->screen_name !== $userInfo['screen_name']) {
                return redirect()->action('ChangeTwitterAccountController@index');
            } else {
                return redirect('trend');
            }

        } else {
            $msg = 'メールアドレスまたはパスワードが違います。';
            $loginError = true;
            return view('login', ['message' => $msg, 'loginError' => $loginError, 'error_email' => $request->email]);
        }
    }

    /**
     * ログアウト処理
     * @return void
     */
    public function logout()
    {
        //セッションクリア
        //アクセストークンだけsessionから破棄
        session()->forget('oauth_token');
        session()->forget('oauth_token_secret');
        // ログアウト処理
        Auth::logout();
        //トップページにリダイレクト
        return redirect('/');
    }
}
