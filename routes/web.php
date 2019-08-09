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

// ログイン前の画面表示
Route::group(['middleware' => ['before.login']], function () {
    // トップページ
    Route::get('/', 'IndexController@index');
    // ログイン
    Route::get('/authLogin', 'AuthLoginController@index');
    // 会員登録
    Route::get('/signup', 'SignUpController@index');
    // パスワードリマインダー送信(認証キー)
    Route::get('/passwordRemindSend', 'PasswordRemindSendController@index');
    // パスワードリマインダー(認証キー)送信
    Route::get('/passwordRemindRecieve', 'PasswordRemindRecieveController@index');
});

// ログイン処理
Route::post('/authLogin', 'AuthLoginController@authLogin');
// 会員登録処理
Route::post('/signup', 'SignUpController@signup');
// パスワードリマインダー送信(認証キー)送信処理
Route::post('/passwordRemindSend', 'PasswordRemindSendController@send');
// パスワードリマインダー送信(再発行パスワード)送信処理
Route::post('/passwordRemindRecieve', 'PasswordRemindRecieveController@send');


// Twitterアカウント変更
Route::get('/changeTwitterAccount', function () {
    return view('changeTwitterAccount');
});

// お問い合わせ(ログイン前)
Route::post('/contact', 'IndexController@contact');

// ===================================================
// ログイン後

Route::group(['middleware' => ['after.login']], function () {
    // 仮想通貨トレンド画面
    Route::get('/trend', 'TrendController@index')->name('trend');
    // 仮想通貨関連アカウント一覧画面
    Route::get('/account', 'AccountController@index')->name('account');
    // 仮想通貨関連ニュース
    Route::get('/news', 'NewsController@index')->name('news');
    // マイページ
    Route::get('/mypage', 'MypageController@index')->name('mypage');
});

// 仮想通貨トレンド画面 axios連携ルーティング
Route::post('/trend/get', 'TrendController@getTrend');
Route::post('/trend/getUpdated', 'TrendController@getUpdated');

// 仮想通貨関連アカウント一覧画面 axios連携ルーティング
Route::post('/account/get', 'AccountController@getAccount');
Route::post('/account/user', 'AccountController@getUser');
Route::post('/account/auto', 'AccountController@autoFollow');
Route::post('/account/follow', 'AccountController@follow');
Route::post('/account/unfollow', 'AccountController@unFollow');


// ====================================================================
// Twitter認証処理のルーティング
// Twitter認証のルーティング
Route::get('/oauth', 'OAuthController@login');
//Callback用のルーティング
Route::get('/callback', 'OAuthController@callBack');

// callbackよりアクセストークンを受け取り、ログイン認証をするルーティング
Route::get('/main', 'OAuthController@main');

//logoutのルーティング
Route::get('/oauthlogout', 'OAuthController@logout');







// ===================================================
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
