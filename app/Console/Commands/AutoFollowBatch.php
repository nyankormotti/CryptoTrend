<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * 自動フォローバッチ
 * usersテーブルのautofollow_flgがtrueのユーザーのみ、自動フォローを実施する
 */
class AutoFollowBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:AutoFollow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '仮想通貨関連アカウントを自動フォロー';

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
        \Log::info('自動フォロー処理開始');

        DB::beginTransaction();
        try {
            // user情報を取得(自動フォローONのユーザーのみ取得)
            $users = User::where('autofollow_flg', 1)
                    ->where('delete_flg', 0)
                    ->get();

            // ユーザーの数だけ繰り返し実施
            // (1ユーザーに付き、1アカウントのみ自動フォローを実施する(30分ごとに実施))
            for ($i = 0; $i < count($users); $i++) {
                \Log::info(($i + 1).'人目のユーザー');
                if($users[$i]->update_flg == 1) {
                    // アカウント情報をバッチで更新中の場合
                    // 60秒待機
                    sleep(60);
                }
                // userのuser_idに紐付くaccountsテーブルのレコードを取得
                // (フォローしていないアカウントのみ取得)
                $accounts = DB::table('accounts')
                            ->where('user_id', $users[$i]->id)
                            ->where('follow_flg', 0)
                            ->get();

                if (count($accounts) == 0) {
                    // アカウント情報が取得できなかった時
                    \Log::info('アカウント情報がありません。');
                    \Log::info('次のユーザーの処理を実施します。');
                    continue;
                }
                // オブジェクトを配列形式に変換
                $array_account = json_decode(json_encode($accounts), true);

                // ランダムにアカウントを1つフォローする
                // アカウント情報からランダムに1つのアカウントを選択し、twitter_idを取得
                $twitter_id = $accounts[array_rand($array_account)]->twitter_id;

                // user情報からアクセストークンを取得
                $oauth_token = $users[$i]->oauth_token;
                $oauth_token_secret = $users[$i]->oauth_token_secret;

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

                // Twitterアプリ内アカウント確認処理開始
                // 指定したアカウントがTwitterアプリ内にて未フォローであることを確認する。
                // Twitter情報より未フォローか確認(API連携)
                $target = $twitter->get('users/show', ["user_id" => $twitter_id]);
                // オブジェクトを配列形式に変換
                $target_account = json_decode(json_encode($target), true);

                if (!empty($target_account['errors'])) {
                    // アカウントが存在しない場合、処理を中断
                    \Log::info('twitter上にアカウントが存在しない');
                    \Log::info('次のユーザーの処理を実施します。');
                    // そのアカウントはフォローできません。
                    continue;

                } else if ($target_account['following'] == true) {
                    // アカウントがフォロー済みの場合
                    \Log::info('フォロー済みのアカウントをフォローしようとしています。');
                    \Log::info('次のユーザーの処理を実施します。');
                    // アカウント情報のfollow_flgをtrueにする。
                    DB::table('accounts')
                        ->where('user_id', $users[$i]->id)
                        ->where('twitter_id', $twitter_id)
                        ->update([
                            'follow_flg' => 1
                        ]);
                    continue;
                }
                // Twitterアプリ内アカウント確認処理終了


                // usersテーブルより初回リクエスト時間を取得
                $first_request_time = new Carbon($users[$i]->first_request_time);
                // usersテーブルより、リクエスト回数を取得
                $request_count = $users[$i]->request_count;
                // usersテーブルより、フォロー回数を取得
                $follow_count = $users[$i]->follow_limit;
                // 現在時刻を取得
                $now_time = new Carbon();

                // リクエスト制限判定その1 開始
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
                        \Log::info('15分間のリクエスト回数の上限を超えています。');
                        \Log::info('次のユーザーの処理を実施します。');
                        // リクエスト回数が15回以上の場合
                        // 処理をスキップ(次のユーザーの自動フォローを実施)
                        continue;
                    }
                } elseif (900 <= $first_request_time->diffInSeconds($now_time) && $request_count != 0) {
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
                // リクエスト制限判定その1 終了

                // リクエスト制限判定その2 開始
                if (24 >= $follow_count) {
                    \Log::info('フォローした回数が24回以下');
                    // フォローした回数が24回以下の場合
                    // フォロー回数を+1回
                    $follow_count = $follow_count + 1;
                } else {
                    \Log::info('フォローした回数が25回以上');
                    \Log::info('フォロー上限回数を超えています');
                    \Log::info('次のユーザーの処理を実施します。');
                    // フォローした回数が25回以上の場合
                    // 処理を中断
                    // フォロー上限回数を超えています
                    continue;
                }
                // リクエスト制限判定その2 終了

                // usersテーブルを更新
                $users[$i]->request_count = $request_count; // リクエスト回数の更新
                $users[$i]->follow_limit = $follow_count; //フォロー回数の更新
                $users[$i]->first_request_time = $first_request_time; // リクエスト時間の更新
                $users[$i]->save();

                // 自動フォロー処理開始
                // API連携(フォローリクエスト実施)
                $result = $twitter->post('friendships/create', ["user_id" => $twitter_id]);
                // オブジェクトを配列形式に変換
                $result_array = json_decode(json_encode($result), true);

                if (!empty($result_array['errors'])) {
                    // 処理結果がエラーだった場合
                    \Log::info('API連携エラー');
                    // 処理を中断
                    // そのアカウントはフォローできません。
                    continue;
                } else {
                    \Log::info('自動フォロー成功');
                    // アカウント情報のfollow_flgをtrueにする。
                    DB::table('accounts')
                        ->where('user_id', $users[$i]->id)
                        ->where('twitter_id', $twitter_id)
                        ->update([
                            'follow_flg' => 1
                        ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }

        \Log::info('自動フォロー処理終了');
        \Log::info('===============');
    }
}
