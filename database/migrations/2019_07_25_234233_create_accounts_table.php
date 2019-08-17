<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 「仮想通貨」のキーワードを含むTwitterアカウントの情報を登録するテーブル
 * (登録するアカウントは現在日時より過去1ヵ月のアクティブユーザーのみ)
 */
class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->integer('user_id')->index()->comment('ユーザーid');
            $table->string('twitter_id')->index()->comment('Twitterアカウントのid');
            $table->string('screen_name')->comment('Twitterアカウントのスクリーンネーム');
            $table->string('account_name')->comment('Twitterアカウントのアカウント名');
            $table->integer('follow')->unsigned()->comment('Twitterアカウントのフォロー数');
            $table->integer('follower')->unsigned()->comment('Twitterアカウントのフォロワー数');
            $table->string('profile')->comment('Twitterアカウントのプロフィール');
            $table->string('recent_tweet')->comment('Twitterアカウントの最新ツイート');
            $table->datetime('last_updated')->comment('Twitterアカウントの最終更新日時');
            $table->boolean('follow_flg')->comment('ユーザーidのアカウントがこのtwitter_idのアカウントをフォローしているかのフラグ');
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
        Schema::dropIfExists('accounts');
    }
}
