<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->index();
            $table->string('email');
            $table->string('password');
            $table->string('twitter_id')->unique()->nullable();
            $table->string('screen_name');
            $table->string('oauth_token');
            $table->string('oauth_token_secret');
            $table->integer('follow_limit')->unsigned();
            $table->integer('unfollow_limit')->unsigned();
            $table->boolean('autofollow_flg')->default(false);
            $table->boolean('delete_flg')->default(false);
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
