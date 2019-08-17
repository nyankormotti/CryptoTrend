<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * ユーザー情報を登録するテーブル
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->index()->comment('usersテーブルのid');
            $table->string('email')->comment('メールアドレス');
            $table->string('password')->comment('パスワード');
            $table->string('twitter_id')->unique()->nullable()->comment('登録したTwitterアカウントのtwitter_id');
            $table->string('screen_name')->comment('登録したTwitterアカウントのスクリーンネーム');
            $table->string('oauth_token')->comment('登録したTwitterアカウントのアクセストークン');
            $table->string('oauth_token_secret')->comment('登録したTwitterアカウントのアクセスシークレットトークン');
            $table->datetime('follow_request_time')->comment('フォローするためにTwitterAPIに連携した際の時間(15分間で初めて連携した時の時間, 現在日時より15分以上過去の場合はリセットする)');
            $table->integer('follow_request_count')->unsigned()->comment('フォローするためにTwitterAPIの連携回数(初めて連携した時間からの連携回数をカウント, 現在日時より15分以上過去の場合はリセットする)');
            $table->datetime('unfollow_request_time')->comment('フォロー解除するためにTwitterAPIに連携した際の時間(15分間で初めて連携した時の時間, 現在日時より15分以上過去の場合はリセットする)');
            $table->integer('unfollow_request_count')->unsigned()->comment('フォロー解除するためにTwitterAPIの連携回数(初めて連携した時間からの連携回数をカウント, 現在日時より15分以上過去の場合はリセットする)');
            $table->integer('follow_limit')->unsigned()->comment('1日の間にフォローした回数(上限値25回、初期値0)');
            $table->integer('unfollow_limit')->unsigned()->comment('1日の間にフォロー解除した回数(上限値25回、初期値0)');
            $table->boolean('autofollow_flg')->default(false)->comment('自動フォローフラグ(0:OFF, 1:ON)');
            $table->boolean('delete_flg')->default(false)->comment('削除フラグ');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
