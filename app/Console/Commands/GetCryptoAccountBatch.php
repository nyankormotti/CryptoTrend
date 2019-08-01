<?php

namespace App\Console\Commands;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Abraham\TwitterOAuth\TwitterOAuth;

class GetCryptoAccountBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:CryptoAccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '仮想通貨関連のtwitterアカウント情報を取得';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('===============');
        \Log::info('アカウント集計バッチ開始');

        // Accountテーブルに最新のtwitterアカウント情報を追加
        $user = User::where('delete_flg', '!=', '1')->get();
        foreach ($user as $a_user) {
            $a_user->update_flg = 1;
            $a_user->save();
        }

        // Accountテーブル全件削除
        Account::query()->truncate();

        for ($i = 0; $i < count($user); $i++) {

            // User情報からアクセストークンを取得
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
            // 現在日時より１ヶ月
            $one_month_ago->subMonth();;

            for ($j = 0; $j < 100; $j++) {

                $params = array(
                    'q'     => '仮想通貨',
                    'page'  => $j + 1,
                    'count' =>  20,
                );

                // TwitterAPI実行(関連仮想通貨アカウント取得)
                $account = $twitter->get('users/search', $params);
                // オブジェクトを配列形式に変換
                $twitter_account = json_decode(json_encode($account), true);

                // アカウントが取得できなかった場合、処理を終了する。
                if (!empty($twitter_account['errors'])) {
                    break;
                }

                // 関連アカウントをAccountテーブルに格納する。
                for ($k = 0; $k < count($twitter_account); $k++) {

                    // アカウントが取得できなかった場合、処理を終了する。
                    if (empty($twitter_account[$k]['status']['created_at'])) {
                        break;
                    }
                    // アカウントの最終更新日を取得
                    $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$k]['status']['created_at']));
                    // 最終更新日が現在日時より過去1週間以上であるアカウントはAccountテーブルに格納しない。
                    if ($account_date < $one_month_ago) {
                        continue;
                    }

                    // 自分と同じアカウントはAccoutテーブルには格納しない。
                    if($user[$i]->screen_name == $twitter_account[$k]['screen_name']){
                        continue;
                    }

                    // Accountテーブルへ格納
                    $account = new Account();
                    $account->user_id = $user[$i]->id;
                    $account->twitter_id = $twitter_account[$k]['id_str'];
                    $account->screen_name = $twitter_account[$k]['screen_name'];
                    $account->account_name = $twitter_account[$k]['name'];
                    $account->follow = $twitter_account[$k]['friends_count'];
                    $account->follower = $twitter_account[$k]['followers_count'];
                    $account->profile = $twitter_account[$k]['description'];
                    $account->recent_tweet = $twitter_account[$k]['status']['text'];
                    $account->follow_flg = $twitter_account[$k]['following'];
                    $account->save();

                }
            }
            $user[$i]->update_flg = 0;
            $user[$i]->save();
        }

        
        \Log::info('アカウント集計バッチ終了');
        \Log::info('===============');
    }
}
