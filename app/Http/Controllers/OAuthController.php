<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

class OAuthController extends Controller
{

    //アクセスしてTwitterのOAuth認証のページまでリダイレクトするところまで記述する
    public function login()
    {
        //インスタンス生成
        $twitter = new TwitterOAuth(env('TWITTER_CLIENT_KEY'), env('TWITTER_CLIENT_SECRET'));

        //リクエストトークン取得
        //リクエストトークンは認証用のURLを生成するのに必要になります
        //'oauth/request_token'はリクエストークンを取得するためのAPIのリソース
        $request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => env('TWITTER_CLIENT_CALLBACK')));

        //認証用URL取得
        //'oauth/authorize'は認証URLを取得するためのAPIのリソース
        $url = $twitter->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

        //認証ページにリダイレクト
        return redirect($url);
    }
    //callBack後の処理について書く(アクセストークンとか取得する)
    public function callBack()
    {
        //GETパラメータから認証トークン取得
        $oauth_token = Input::get('oauth_token');
        //GETパラメータから認証キー取得
        $oauth_verifier = Input::get('oauth_verifier');

        //インスタンス生成
        $twitter = new TwitterOAuth(
            //API Key
            env('TWITTER_CLIENT_KEY'),
            //API Secret
            env('TWITTER_CLIENT_SECRET'),
            //認証トークン
            $oauth_token,
            //認証キー
            $oauth_verifier
        );

        //アクセストークン取得
        //'oauth/access_token'はアクセストークンを取得するためのAPIのリソース
        $accessToken = $twitter->oauth('oauth/access_token', array('oauth_token' => $oauth_token, 'oauth_verifier' => $oauth_verifier));

        // var_dump($accessToken);exit;

        //セッションにアクセストークンを登録
        session()->put('accessToken', $accessToken);

        //indexページにリダイレクト
        return redirect('index');
    }

    //アクセストークンを使用してAPIを叩いて結果をビューに受け渡す
    public function index()
    {
        //セッションからアクセストークン取得
        $accessToken = session()->get('accessToken');

        //インスタンス生成
        $twitter = new TwitterOAuth(
            //API Key
            env('TWITTER_CLIENT_KEY'),
            //API Secret
            env('TWITTER_CLIENT_SECRET'),
            //アクセストークン
            $accessToken['oauth_token'],
            $accessToken['oauth_token_secret']
        );

        //ユーザ情報を取得
        //'account/verify_credentials'はユーザ情報を取得するためのAPIのリソース
        // get_object_vars()でオブジェクトの中身をjsonで返す
        $userInfo = get_object_vars($twitter->get('account/verify_credentials'));

        // $i = json_decode($userInfo,true);
        //twitterというビューにユーザ情報が入った$userInfoを受け渡す
        return view('twitter', ['userInfo' => $userInfo]);
    }
    //ログアウト処理(今回は最終的にwelcomeページにリダイレクトするようにする)
    public function logout()
    {
        //セッションクリア
        session()->flush();

        //OAuthログイン画面にリダイレクト
        return redirect('/');
    }
}
