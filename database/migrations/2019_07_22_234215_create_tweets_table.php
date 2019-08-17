<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 各銘柄のツイート数を登録するテーブル
 */
class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id')->comment('tweetsテーブルのid');
            $table->integer('currency_id')->unsigned()->comment('銘柄のid ');
            $table->integer('tweet_count')->unsigned()->comment('ツイート数');
            $table->datetime('acquisition_date')->index()->comment('ツイート数を更新した日時');
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
        Schema::dropIfExists('tweets');
    }
}
