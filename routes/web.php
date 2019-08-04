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
Route::post('/account/user', 'AccountController@getUser');
Route::post('/account/auto', 'AccountController@autoFollow');
Route::post('/account/follow', 'AccountController@follow');
Route::post('/account/unfollow', 'AccountController@unFollow');

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



//ログイン認証するためのルーティング
Route::get('/oauth', 'OAuthController@login');

//Callback用のルーティング
Route::get('/callback', 'OAuthController@callBack');

//indexのルーティング
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
