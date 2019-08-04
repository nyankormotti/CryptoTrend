<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('user_id')->index();
            $table->string('twitter_id')->index();
            $table->string('screen_name');
            $table->string('account_name');
            $table->integer('follow')->unsigned();;
            $table->integer('follower')->unsigned();;
            $table->string('profile');
            $table->string('recent_tweet');
            $table->datetime('last_updated');
            $table->boolean('follow_flg');
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
