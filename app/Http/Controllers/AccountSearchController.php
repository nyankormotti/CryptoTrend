<?php

namespace App\Http\Controllers;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Abraham\TwitterOAuth\TwitterOAuth;

class AccountSearchController extends Controller
{
    public function index(){

        // Accountテーブル全件削除
        Account::query()->truncate();
        // $account->delete();
        // var_dump($account);
        // exit;

        // Accountテーブルに最新のtwitterアカウント情報を追加
        $user = User::all();

        // var_dump(count($user));exit;

        for($i = 0; $i < count($user); $i++){

            $oauth_token = $user[$i]->oauth_token;
            $oauth_token_secret = $user[$i]->oauth_token_secret;

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

            $one_month_ago = new Carbon();
            $one_month_ago->subDay(3);

            $account_count = 0;

            for ($j = 0; $j < 100; $j++) {

                $params = array(
                    'q'     => '仮想通貨',
                    'page'  => $j + 1,
                    'count' =>  20,
                );

                $account = $twitter->get('users/search', $params);
                $twitter_account = json_decode(json_encode($account), true);

                // var_dump($twitter_account);
                // exit;

                if (!empty($twitter_account['errors'])) {
                    break;
                }

                for ($k = 0; $k < count($twitter_account); $k++) {

                    if (empty($twitter_account[$k]['status']['created_at'])) {
                        break;
                    }
                    $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$k]['status']['created_at']));
                    if ($account_date < $one_month_ago) {
                        continue;
                    }

                    // $account = new Account();
                    // $account->user_id = $user[$i]->id;
                    // $account->twitter_id = $twitter_account[$k]['id_str'];
                    // $account->screen_name = $twitter_account[$k]['screen_name'];
                    // $account->account_name = $twitter_account[$k]['name'];
                    // $account->follow = $twitter_account[$k]['friends_count'];
                    // $account->follower = $twitter_account[$k]['followers_count'];
                    // $account->profile = $twitter_account[$k]['description'];
                    // $account->recent_tweet = $twitter_account[$k]['status']['text'];
                    // $account->follow_flg = $twitter_account[$k]['following'];
                    // $account->save();

                    $account_count++;
                }
            }
        }

        var_dump($account_count);
        // exit();

        // // $user = User::where('id', Auth::id())->first();


        // $oauth_token = $user['0']->oauth_token;
        // $oauth_token_secret = $user['0']->oauth_token_secret;

        // //インスタンス生成
        // $twitter = new TwitterOAuth(
        //     //API Key
        //     env('TWITTER_CLIENT_KEY'),
        //     //API Secret
        //     env('TWITTER_CLIENT_SECRET'),
        //     //アクセストークン
        //     $oauth_token,
        //     $oauth_token_secret
        // );
        // // $params = array(
        // //     'q'     => '仮想通貨',
        // //     'page'  => 1,
        // //     'count' =>  20,
        // // );
        // $one_month_ago = new Carbon();
        // $one_month_ago->subDay(1);
        // // var_dump($one_month_ago);exit;

        // $account_count = 0;

        // // $account = $twitter->get('users/search', $params);
        // // var_dump($account);
        // // $twitter_account = json_decode(json_encode($account), true);

        // // var_dump(count($twitter_account));exit;

        // for($i = 0; $i < 100; $i++){

        //     $params = array(
        //         'q'     => '仮想通貨',
        //         'page'  => $i + 1,
        //         'count' =>  20,
        //     );

        //     $account = $twitter->get('users/search', $params);
        //     $twitter_account = json_decode(json_encode($account), true);

        //     var_dump($twitter_account);exit;

        //     if(!empty($twitter_account['errors'])){
        //         break;
        //     }

        //     for($j = 0; $j < count($twitter_account); $j++){

        //         if(empty($twitter_account[$j]['status']['created_at'])){
        //             break;
        //         }
        //         $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$j]['status']['created_at']));
        //         if($account_date < $one_month_ago){
        //             continue;
        //         }

        //         $account_count++;
        //     }
        // }

        // var_dump($account_count);
        // exit();







        // var_dump(empty($twitter_account['errors']));exit;

        // if($twitter_account[0]['status']['created_at'] < $one_month_ago){

        // }
        // $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[0]['status']['created_at']));
        // var_dump($account_date);
        // // var_dump(($twitter_account[0]['status']['created_at']));
        // var_dump($nowday);
        // var_dump($account_date < $nowday);exit;
        // // var_dump($twitter_account[0]['status']['created_at']);exit;

        // return view('Account');
    }
}
