<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Libraries\CommonFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Requests\ChangeTwitterAccount;

/**
 * 仮想通貨関連アカウント変更コントローラ
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
        // エラーメッセージがある場合(changeAccountMainからリダイレクトした場合)
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
     * セッションに画面で入力した値と、Twitterアカウント変更画面から遷移した意味を示すフラグを格納し、Twitter認証処理へリダイレクトする
     * @param ChangeTwitterAccount $request (screen_name)
     * @return void
     */
    public function changeAccount(ChangeTwitterAccount $request)
    {
        // セッションに格納
        session()->put('screen_name', $request->screen_name); //Twitterアカウント変更画面で入力したTwitterアカウント(screen_name)
        session()->put('change_account_flg', true);//Twitterアカウント変更画面から遷移した意味を示すフラグ(OAuthControllerのcallBackメソッドにて使用)
        // Twitter API認証処理を実施
        return redirect()->action('OAuthController@login');
    }

    /**
     * TwitterAPI連携後のアカウント変更処理 (Twitter API認証のcallback処理後に遷移してくる)
     * Twitter上の自身のアカウント情報を取得し、画面で入力したscreen_nameを比較
     * 同じであれば、usersテーブルのscreen_nameを更新、異なれば、Twitterアカウント変更画面へリダイレクト
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
            $user->follow_request_time = new Carbon();
            $user->follow_request_count = 0;
            $user->unfollow_request_time = new Carbon();
            $user->unfollow_request_count = 0;
            $user->follow_limit = 0;
            $user->unfollow_limit = 0;
            $user->autofollow_flg = 0;
            // Usersテーブル更新処理
            $user->save();

            // 更新したuser情報を取得
            $user = User::where('id', Auth::id())->first();
            // ユーザーのuser_idに紐付くaccountsテーブルのレコードを削除
            DB::table('accounts')
                ->where(
                    'user_id',
                    $user->id
                )->delete();

            // 登録したユーザー情報にひもづく仮想通貨関連アカウントを取得
            // 共通関数呼び出し
            $commonFunc = new CommonFunctions;
            $commonFunc->getAccount($user);

        } else {
            // Twitterのscreen_nameのみ変更する場合
            $user->screen_name = $userInfo['screen_name'];
            // Usersテーブル更新処理
            $user->save();
        }

        return redirect()->action('TrendController@index');
    }
}
