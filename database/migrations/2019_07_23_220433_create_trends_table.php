<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 各銘柄のツイート数を1時間、1日、１週間ごとに集計するテーブル
 * (tweetsテーブルのデータを元に集計する)
 */
class CreateTrendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trends', function (Blueprint $table) {
            $table->increments('id')->comment('trendsテーブルのid');
            $table->integer('period_id')->unsigned()->comment('集計期間のid (1:1時間, 2:1日, 3:１週間)');
            $table->integer('currency_id')->unsigned()->comment('銘柄のid (currenciesテーブルのidと紐付く)');
            $table->integer('tweet_count')->comment('ツイート数');
            $table->datetime('acquisition_date')->comment('ツイート数を更新した日時');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trends');
    }
}
