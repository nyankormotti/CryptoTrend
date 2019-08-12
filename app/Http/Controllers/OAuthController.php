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
        //'oauth/request_token'はリクエストークンを取得するためのAPIのリソース
        $request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => env('TWITTER_CLIENT_CALLBACK')));

        //認証用URL取得
        //'oauth/authorize'は認証URLを取得するためのAPIのリソース
        $url = $twitter->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        //TwitterAPI認証ページにリダイレクト
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

        //セッションにアクセストークンを登録
        session()->put('oauth_token', $accessToken['oauth_token']);
        session()->put('oauth_token_secret', $accessToken['oauth_token_secret']);

        if(!empty(session()->get('change_account_flg'))) {
            // Twitterアカウント変更画面からの遷移の場合、Twitterアカウント変更画面にリダイレクト
            session()->forget('change_account_flg');
            return redirect('changeTwitterAccountMain');
            return redirect()->action('ChangeTwitterAccountController@changeAccountMain');
        } else{
            //indexページにリダイレクト
            return redirect('main');
        }
    }

    //アクセストークンを使用してAPIを叩いて結果をビューに受け渡す
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

        $screen_name = session()->get('screen_name');
        $email = session()->get('email');
        $password = session()->get('password');

        if($screen_name !== $userInfo['screen_name']){
            // screen_nameがTwitterのユーザー情報と異なる場合
            // 会員登録画面にリダイレクト
            // session()->flush();
            $msg = 'Twitterアカウントが存在しません。';
            session()->put('message', $msg);
            \Log::info('Outhの連携');
            \Log::info(session()->get('message'));
            // return view('signup', ['message' => $msg, 'err_screen_name' => $err_screen_name, 'err_email' => $err_email]);
            // return redirect()->action('SignUpController@index', ['message' => $msg, 'err_screen_name' => $err_screen_name, 'err_email' => $err_email]);
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
        $user->first_request_time = new Carbon();
        $user->request_count = 0;
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

            for ($i = 0; $i < count($twitter_account); $i++) {

                // アカウントの情報が取得できなかった場合、処理を終了させる
                if (empty($twitter_account[$i]['status']['created_at'])) {
                    break;
                }
                $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$i]['status']['created_at']));

                // 活動時間が現在日時よりも1日より過去だった場合、DBへのアカウント情報の格納処理をスキップする。
                if ($account_date < $one_month_ago) {
                    continue;
                }

                // 自分と同じアカウントはAccoutテーブルには格納しない。
                if ($screen_name == $twitter_account[$i]['screen_name']) {
                    continue;
                }

                // アカウント情報をAccountテーブルに格納
                $account = new Account();
                $account->user_id = $id;
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

        //twitterというビューにユーザ情報が入った$userInfoを受け渡す
        return redirect('trend');
        // return view('trend');
    }
    //ログアウト処理
    public function logout()
    {
        //セッションクリア
        //アクセストークンだけsessionから破棄
        session()->forget('oauth_token');
        session()->forget('oauth_token_secret');

        // ログアウト処理
        Auth::logout();

        //OAuthログイン画面にリダイレクト
        return redirect('/');
    }
}
