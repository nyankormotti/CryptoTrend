<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

// ログイン
Route::get('/authLogin', 'AuthLoginController@index');
Route::post('/authLogin', 'AuthLoginController@authLogin');

// 会員登録
Route::get('/signup', 'SignUpController@index');
Route::post('/signup', 'SignUpController@signup');

Route::get('/home', 'HomeController@index')->name('home');

// Google news
Route::get('/google', 'GoogleController@sample')->name('google');

// ツイート数
Route::get('/tweet', 'TweetCountController@index')->name('tweet');

// coincheck
Route::get('/coincheck', 'CoincheckController@index')->name('coin');


//ログイン認証するためのルーティング
Route::get('/oauth', 'OAuthController@login');

//Callback用のルーティング
Route::get('/callback', 'OAuthController@callBack');

//indexのルーティング
Route::get('/main', 'OAuthController@main');

//logoutのルーティング
Route::get('/oauthlogout', 'OAuthController@logout');

//logout後のリダイレクト先
Route::get('/', function () {
    return view('welcome');
});

// Route::get('login/twitter', 'Auth\SocialAccountController@redirectToProvider');
// Route::get('login/twitter/callback', 'Auth\SocialAccountController@handleProviderCallback');

// Route::get('auth/twitter', 'Auth\AuthController@redirectToProvider');
// Route::get('auth/twitter/callback', 'Auth\AuthController@handleProviderCallback');
// Route::get("auth/twitter/logout", "Auth\AuthController@getLogout");

// Route::get("main", array("as" => "main", "uses" => function () {
//     return view("main");
// }));
