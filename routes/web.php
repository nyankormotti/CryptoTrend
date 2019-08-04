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

// トップページ
Route::get('/', function () {
    return view('index');
});

// Auth::routes();

// ログイン
Route::get('/authLogin', 'AuthLoginController@index');
Route::post('/authLogin', 'AuthLoginController@authLogin');

// 会員登録
Route::get('/signup', 'SignUpController@index');
Route::post('/signup', 'SignUpController@signup');

//logout後のリダイレクト先
Route::get('/', function () {
    return view('index');
});

// パスワードリマインダー送信
Route::get('/passwordRemindSend', function () {
    return view('passwordRemindSend');
});
// パスワードリマインダー(認証キー)送信
Route::get('/passwordRemindRecieve', function () {
    return view('passwordRemindRecieve');
});
// Twitterアカウント変更
Route::get('/changeTwitterAccount', function () {
    return view('changeTwitterAccount');
});

// ===================================================
// ログイン後

// 仮想通貨トレンド画面
Route::get('/trend', function () {
    return view('trend');
})->name('trend');

Route::post('/trend/get', 'TrendController@index');

// 仮想通貨関連アカウント一覧画面
Route::get('/account', function () {
    return view('account');
})->name('account');

Route::post('/account/get', 'AccountController@index');
Route::post('/account/count', 'AccountController@totalCount');

// 仮想通貨関連ニュース
Route::get('/news', 'NewsController@index')->name('news');
// Route::get('/news', function () {
//     return view('news');
// })->name('news');

// Route::get('/news/get', 'NewsController@index');



// マイページ
Route::get('/mypage', function () {
    return view('mypage');
});

// 検証用コントローラ
// ツイート数
Route::get('/tweet', 'TweetCountController@index')->name('tweet');

// アカウント数
Route::get('/accountSearch', 'AccountSearchController@index')->name('accountSearch');

// coincheck
Route::get('/coincheck', 'CoincheckController@index')->name('coin');

// アカウントテーブルからの取得

// 検証用コントローラ


// ===================================================

//ログイン認証するためのルーティング
Route::get('/oauth', 'OAuthController@login');

//Callback用のルーティング
Route::get('/callback', 'OAuthController@callBack');

//indexのルーティング
Route::get('/main', 'OAuthController@main');

//logoutのルーティング
Route::get('/oauthlogout', 'OAuthController@logout');



// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('login/twitter', 'Auth\SocialAccountController@redirectToProvider');
// Route::get('login/twitter/callback', 'Auth\SocialAccountController@handleProviderCallback');

// Route::get('auth/twitter', 'Auth\AuthController@redirectToProvider');
// Route::get('auth/twitter/callback', 'Auth\AuthController@handleProviderCallback');
// Route::get("auth/twitter/logout", "Auth\AuthController@getLogout");

// Route::get("main", array("as" => "main", "uses" => function () {
//     return view("main");
// }));
