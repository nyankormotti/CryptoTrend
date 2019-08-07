<?php

namespace App\Http\Controllers;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @return $action_flg (API連携成功の有無フラグ)
     */
    public function follow(Request $request) {
        \Log::info('===============');
        \Log::info('手動フォロー開始');

        // API連携成功の有無フラグ
        // 1:成功
        // 1以外：失敗 (数値によってVue側でメッセージを変えたalertを表示させる)
        $action_flg = 1;

        // セッションのユーザーIDよりUser情報を取得
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

        // Twitter情報より未フォローか確認(API連携)
        $target= $twitter->get('users/show', ["user_id" => $request->twitter_id]);
        // オブジェクトを配列形式に変換
        $target_account = json_decode(json_encode($target), true);

        // アカウントが存在しない場合、処理を中断
        if(!empty($target_account['errors'])) {
            \Log::info('エラー:アカウントが存在しない');
            // 処理を中断
            // そのアカウントはフォローできません。
            $action_flg = 0;
            return $action_flg ;
        } 
        // アカウントがフォロー済みの場合
        else if($target_account['following'] == true) {
            \Log::info('エラー：フォロー済みのアカウントをフォローしようとしています。');
            // アカウント情報のfollow_flgをtrueにする。
            DB::table('accounts')
                ->where('user_id', Auth::id())
                ->where('twitter_id', $request->twitter_id)
                ->update([
                    'follow_flg' => 1
                ]);
            // API連携成功の有無フラグをfalseに変更
            $action_flg = 4;
            return $action_flg;
        }

        // usersテーブルより初回リクエスト時間を取得
        $first_request_time = new Carbon($user->first_request_time);
        // usersテーブルより、リクエスト回数を取得
        $request_count = $user->request_count;
        // usersテーブルより、フォロー回数を取得
        $follow_count = $user->follow_limit;
        // 現在時刻を取得
        $now_time = new Carbon();

        // リクエスト制限判定その１
        if(900 > $first_request_time->diffInSeconds($now_time)) {
            \Log::info('初回リクエストから15分未満');
            // 初回リクエストした時間から15分経過していない場合
            if(15 > $request_count){
                \Log::info('リクエスト回数が15回未満');
                // リクエスト回数が15回未満の場合
                // リクエスト回数+1回
                $request_count = $request_count + 1;
            } else {
                \Log::info('リクエスト回数が15回以上');
                \Log::info('エラー：15分間のリクエスト回数を超えています');
                // リクエスト回数が15回以上の場合
                // 処理を中断
                // 15分間のリクエスト回数を超えています
                $action_flg = 2;
                return $action_flg;
            }
        } elseif(900 <= $first_request_time->diffInSeconds($now_time) && $request_count != 0) {
            \Log::info('初回リクエストから15分以上');
            \Log::info('リクエスト回数を初期化');
            \Log::info('リクエスト時間を初期化');
            // 初回リクエストした時間から15分以上経過した場合
            // 現在時刻を初回リクエスト時間に登録
            // リクエスト回数を初期化
            $request_count = 1;
            // リクエスト時間を現在時刻に更新
            $first_request_time = new Carbon();
        }

        // リクエスト制限判定その2
        if(24 >= $follow_count){
            \Log::info('フォローした回数が24回以下');
            // フォローした回数が24回以下の場合
            // フォロー回数を+1回
            $follow_count = $follow_count + 1;
        } else {
            \Log::info('フォローした回数が25回以上');
            \Log::info('エラー：フォロー上限回数を超えています');
            // フォローした回数が25回以上の場合
            // 処理を中断
            // フォロー上限回数を超えています
            $action_flg = 3;
            return $action_flg;
        }
        //  usersテーブルを更新
        $user->request_count = $request_count;
        $user->follow_limit = $follow_count;
        $user->first_request_time = $first_request_time;
        $user->save();

        // API連携(フォローリクエスト実施)
        $result = $twitter->post('friendships/create', ["user_id" => $request->twitter_id]);
        // オブジェクトを配列形式に変換
        $result_array = json_decode(json_encode($result), true);
        // API連携が失敗した場合、API連携成功の有無フラグをfalseにする。
        if(!empty($result_array['errors'])) {
            \Log::info('エラー:アカウントが存在しない');
            // 処理を中断
            // そのアカウントはフォローできません。
            $action_flg = 0;
            return $action_flg;
        } else {
            \Log::info('手動フォロー成功');
            // アカウント情報のfollow_flgをtrueにする。
            DB::table('accounts')
            ->where('user_id', Auth::id())
            ->where('twitter_id', $request->twitter_id)
            ->update([
                'follow_flg' => 1
            ]);
        }
        \Log::info('手動フォロー処理終了');
        \Log::info('===============');

        return $action_flg;
    }

    /**
     * フォロー解除処理(API連携)
     * @param $request (twitter_id)
     * @return $action_flg (API連携の成功の有無)
     */
    public function unfollow(Request $request)
    {
        \Log::info('===============');
        \Log::info('手動フォロー解除開始');

        // API連携成功の有無フラグ
        // 1:成功
        // 1以外：失敗 (数値によってVue側でメッセージを変えたalertを表示させる)
        $action_flg = 1;

        // セッションのユーザーIDよりUser情報を取得
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

        // Twitter情報より未フォローか確認(API連携)
        $target = $twitter->get('users/show', ["user_id" => $request->twitter_id]);
        // オブジェクトを配列形式に変換
        $target_account = json_decode(json_encode($target), true);

        // アカウントが存在しない場合、処理を中断
        if (!empty($target_account['errors'])) {
            \Log::info('エラー:アカウントが存在しない');
            // 処理を中断
            // そのアカウントはフォローできません。
            $action_flg = 0;
            return $action_flg;
        }
        // アカウントが未フォローの場合
        else if ($target_account['following'] == false) {
            \Log::info('エラー：未フォローのアカウントをフォロー解除しようとしています。');
            // アカウント情報のfollow_flgをtrueにする。
            DB::table('accounts')
                ->where('user_id', Auth::id())
                ->where('twitter_id', $request->twitter_id)
                ->update([
                    'follow_flg' => 0
                ]);
            // API連携成功の有無フラグをfalseに変更
            $action_flg = 4;
            return $action_flg;
        }

        // usersテーブルより初回リクエスト時間を取得
        $first_request_time = new Carbon($user->first_request_time);
        // usersテーブルより、リクエスト回数を取得
        $request_count = $user->request_count;
        // usersテーブルより、フォロー回数を取得
        $unfollow_count = $user->unfollow_limit;
        // 現在時刻を取得
        $now_time = new Carbon();

        // リクエスト制限判定その１
        if (900 > $first_request_time->diffInSeconds($now_time)) {
            \Log::info('初回リクエストから15分未満');
            // 初回リクエストした時間から15分経過していない場合
            if (15 > $request_count) {
                \Log::info('リクエスト回数が15回未満');
                // リクエスト回数が15回未満の場合
                // リクエスト回数+1回
                $request_count = $request_count + 1;
            } else {
                \Log::info('リクエスト回数が15回以上');
                \Log::info('エラー：15分間のリクエスト回数を超えています');
                // リクエスト回数が15回以上の場合
                // 処理を中断
                // 15分間のリクエスト回数を超えています
                $action_flg = 2;
                return $action_flg;
            }
        } else if(900 <= $first_request_time->diffInSeconds($now_time) && $request_count != 0) {
            \Log::info('初回リクエストから15分以上');
            \Log::info('リクエスト回数を初期化');
            \Log::info('リクエスト時間を初期化');
            // リクエスト回数が1回以上であり、初回リクエストした時間から15分以上経過した場合
            // 現在時刻を初回リクエスト時間に登録
            // リクエスト回数を初期化
            $request_count = 1;
            // リクエスト時間を現在時刻に更新
            $first_request_time = new Carbon();
            
        }

        // リクエスト制限判定その2
        if (99 >= $unfollow_count) {
            \Log::info('フォロー解除した回数が100回以下');
            // フォローした回数が99回以下の場合
            // フォロー回数を+1回
            $unfollow_count = $unfollow_count + 1;
        } else {
            \Log::info('フォロー解除した回数が100回以上');
            \Log::info('エラー：フォロー解除上限回数を超えています');
            // フォローした回数が100回以上の場合
            // 処理を中断
            // フォロー上限回数を超えています
            $action_flg = 3;
            return $action_flg;
        }
        //  usersテーブルを更新
        $user->request_count = $request_count;
        $user->unfollow_limit = $unfollow_count;
        $user->first_request_time = $first_request_time;
        $user->save();

        // API連携(フォローリクエスト実施)
        $result = $twitter->post('friendships/destroy', ["user_id" => $request->twitter_id]);
        // オブジェクトを配列形式に変換
        $result_array = json_decode(json_encode($result), true);
        // API連携が失敗した場合、API連携成功の有無フラグをfalseにする。
        if (!empty($result_array['errors'])) {
            \Log::info('エラー:アカウントが存在しない');
            // 処理を中断
            // そのアカウントはフォロー解除できません。
            $action_flg = 0;
            return $action_flg;
        } else {
            \Log::info('手動フォロー解除成功');
            // アカウント情報のfollow_flgをtrueにする。
            DB::table('accounts')
                ->where('user_id', Auth::id())
                ->where('twitter_id', $request->twitter_id)
                ->update([
                    'follow_flg' => 0
                ]);
        }

        \Log::info('手動フォロー解除処理終了');
        \Log::info('===============');

        return $action_flg;
    }
}
