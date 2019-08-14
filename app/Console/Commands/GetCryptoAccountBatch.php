<?php

namespace App\Console\Commands;

use App\User;
use App\Account;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();
        try {
            // ユーザー情報を取得
            $user = User::where('delete_flg', 0)->get();

            // 15分間のAPIリクエスト上限回数('users/search'のリクエスト制限は1ユーザー15分間で900回まで)
            $RQUEST_LIMIT = 900;

            // ユーザーごとにアカウント情報を更新する
            for ($i = 0; $i < count($user); $i++) {

                // ユーザー情報をアカウント情報更新中に更新
                $user[$i]->update_flg = 1;
                $user[$i]->save();

                // 更新するユーザーのuser_idに紐付くaccountsテーブルのレコードを削除
                DB::table('accounts')
                    ->where('user_id', $user[$i]->id
                    )->delete();

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

                // API連携フラグ(1ユーザーごとのAPI連携のフラグ、処理が終了したらfalseにする。)
                $api_flg = true;

                // リクエスト回数(カウント用)(15分間におけるリクエスト回数(上限は900回))
                $request_count = 0;
                // ページカウント
                $page_count = 0;

                // 現在日時より１ヶ月前の日付を取得
                $one_month_ago = new Carbon();
                $one_month_ago->subMonth();

                // 初回API連携の時間を取得
                $start_time = new Carbon();

                // Twitter API連携処理
                while($api_flg) {
                    // ページカウントのカウントアップ
                    $page_count = $page_count + 1;

                    // 検索条件
                    $params = array(
                        'q'     => '仮想通貨',
                        'page'  => $page_count,
                        'count' =>  20,
                    );

                    // 現在時刻を取得
                    $now_time = new Carbon();

                    // 初めのAPIリクエストより15分以上経過していないしていない
                    // かつリクエスト回数が上限(900回)に達した場合
                    if(900 > $start_time->diffInSeconds($now_time) && $request_count === $RQUEST_LIMIT) {
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
                    // オブジェクトを配列形式に変換
                    $twitter_account = json_decode(json_encode($account), true);

                    // リクエスト回数 カウントアップ
                    $request_count = $request_count + 1;

                    // アカウントが取得できなかった場合、処理を終了する。
                    if (!empty($twitter_account['errors'])) {
                        break;
                    }

                    // 関連アカウントをAccountテーブルに格納する。
                    for ($j = 0; $j < count($twitter_account); $j++) {

                        // アカウントが取得できなかった場合、処理を終了する。
                        if (empty($twitter_account[$j]['status']['created_at'])) {
                            break;
                        }
                        // アカウントの最終更新日を取得
                        $account_date = date('Y-m-d H:i:s', strtotime($twitter_account[$j]['status']['created_at']));

                        // 最終更新日が現在日時より過去1ヶ月以上であるアカウントはAccountテーブルに格納しない。
                        // または、自分と同じアカウントはAccoutテーブルには格納しない。
                        if ($account_date < $one_month_ago || $user[$i]->screen_name == $twitter_account[$j]['screen_name']) {
                            continue;
                        }

                        // Accountテーブルへ格納
                        $account = new Account();
                        $account->user_id = $user[$i]->id;
                        $account->twitter_id = $twitter_account[$j]['id_str'];
                        $account->screen_name = $twitter_account[$j]['screen_name'];
                        $account->account_name = $twitter_account[$j]['name'];
                        $account->follow = $twitter_account[$j]['friends_count'];
                        $account->follower = $twitter_account[$j]['followers_count'];
                        $account->profile = $twitter_account[$j]['description'];
                        $account->recent_tweet = $twitter_account[$j]['status']['text'];
                        $account->last_updated = $account_date;
                        $account->follow_flg = $twitter_account[$j]['following'];
                        $account->save();
                    }
                }
                // ユーザー情報をアカウント情報更新完了に更新
                // update_flg(アカウント更新フラグ)をfalse
                // follow_limit(フォロー回数)を初期化
                // unfollow_limit(フォロー解除回数)を初期化
                $user[$i]->update_flg = 0;
                $user[$i]->follow_limit = 0;
                $user[$i]->unfollow_limit = 0;
                $user[$i]->save();
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }

        \Log::info('アカウント集計バッチ終了');
        \Log::info('===============');
    }
}
