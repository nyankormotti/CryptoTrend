<?php

namespace App\Http\Controllers;

use App\User;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;

class AccountController extends Controller
{

    /**
     * 仮想通貨関連Twitterアカウント取得処理
     * @param $request (follow_flg, last_updated)
     * @return array (アカウント情報)
     */
    public function index(Request $request) {

        $account_list = Account::where('user_id',Auth::id())->where('follow_flg', $request->follow_flg)->orderBy('last_updated','DESC')->get();

        return $account_list;
    }

    /**
     * ユーザー情報取得処理
     * @return array (ユーザー情報)
     */
    public function getUser() {

        $user = User::find(Auth::id());
        return $user;
    }

    /**
     * 自動フォローフラグの登録処理
     * (usersテーブルのautofollow_flgがtrueの場合、バッチ処理にて自動フォローが実施される)
     * @param $request (autoFollow_flg)
     * @return array (ユーザー情報)
     */
    public function autoFollow(Request $request) {

        $user = User::find(Auth::id());
        $user->autofollow_flg = !$request->autoFollow_flg;
        $user->save();

        return $user;
    }

    /**
     * フォロー処理(API連携)
     * @param $request (twitter_id)
     * @return $action_flg (API連携の成功の有無)
     */
    public function follow(Request $request) {

        // API連携成功の有無フラグ
        $action_flg = true;

        // セッションのユーザーIDよりユーザー情報を取得
        $user = User::find(Auth::id());

        // User情報からアクセストークンを取得
        $oauth_token = $user->oauth_token;
        $oauth_token_secret = $user->oauth_token_secret;

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

        // API連携(フォロー)
        $result = $twitter->get('friendships/create', ["user_id" => $request->twitter_id]);

        // オブジェクトを配列形式に変換
        $result_array = json_decode(json_encode($result), true);

        // API連携が失敗した場合、API連携成功の有無フラグをfalseにする。
        if(!empty($result_array['errors'])) {
            $action_flg = false;
        }

        return $action_flg;
    }

    /**
     * フォロー解除処理(API連携)
     * @param $request (twitter_id)
     * @return $action_flg (API連携の成功の有無)
     */
    public function unFollow(Request $request) {

        // セッションのユーザーIDよりユーザー情報を取得
        $action_flg = true;

        // API連携成功の有無フラグ
        $user = User::find(Auth::id());

        // User情報からアクセストークンを取得
        $oauth_token = $user->oauth_token;
        $oauth_token_secret = $user->oauth_token_secret;

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

        // API連携(フォロー解除)
        $result = $twitter->get('friendships/destroy', ["user_id" => $request->twitter_id]);
        // オブジェクトを配列形式に変換
        $result_array = json_decode(json_encode($result), true);

        if(!empty($result_array['errors'])) {
            $action_flg = false;
        }

        // API連携が失敗した場合、API連携成功の有無フラグをfalseにする。
        return $action_flg;
    }


}
