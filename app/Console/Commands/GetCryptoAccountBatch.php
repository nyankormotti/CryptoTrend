<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Libraries\CommonFunctions;
use Illuminate\Support\Facades\DB;

/**
 * 仮想通貨関連アカウント取得バッチ
 * 
 * 「仮想通貨」のキーワードをアカウント名、またはプロフィールに含むTwitterアカウントを取得する
 * (最終更新日時が過去1ヶ月の間にあるアカウントのみ対象)
 */
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

            // ユーザーごとにアカウント情報を更新する
            for ($i = 0; $i < count($user); $i++) {

                // ユーザー情報をアカウント情報更新中に更新
                $user[$i]->update_flg = 1;
                $user[$i]->save();

                // 更新するユーザーのuser_idに紐付くaccountsテーブルのレコードを削除
                DB::table('accounts')
                    ->where('user_id', $user[$i]->id
                    )->delete();

                // 登録したユーザー情報にひもづく仮想通貨関連アカウントを取得
                // 共通関数呼び出し
                $commonFunc = new CommonFunctions;
                $commonFunc->getAccount($user[$i]);

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
