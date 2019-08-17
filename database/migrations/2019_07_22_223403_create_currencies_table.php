<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * 仮想通貨の各銘柄の「24時間当たりの最高取引価格」と「24時間当たりの最安取引価格」を登録するテーブル
 */
class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id')->comment('currenciesテーブルのid');
            $table->string('currency_name')->comment('銘柄の名前');
            $table->integer('max_price')->nullable()->comment('24時間当たりの最高取引価格');
            $table->integer('min_price')->nullable()->comment('24時間当たりの最安取引価格');
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
        Schema::dropIfExists('currencies');
    }
}
