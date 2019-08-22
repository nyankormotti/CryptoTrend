<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Hash;

/**
 * 会員登録コントローラ
 */
class SignUpController extends Controller
{
    /**
     * 会員登録画面表示
     * @return void
     */
    public function index()
    {
        // エラーメッセージがある場合(TwitterAPI認証処理からリダイレクトした場合)
        if (!empty(session()->get('message'))) {
            // エラーメッセージ、Twitterアカウント、メールアドレスのセッション情報を取得
            $msg = session()->get('message');
            $err_screen_name = session()->get('screen_name');
            $err_email = session()->get('email');
            // エラーメッセージ、Twitterアカウント、メールアドレスのセッション情報を削除
            session()->forget('message');
            session()->forget('screen_name');
            session()->forget('email');
            return view('signup', ['message' => $msg, 'err_screen_name' => $err_screen_name, 'err_email' => $err_email]);
        }

        return view('signup');
    }

    /**
     * 会員登録処理
     * 入力値をセッションに格納し、TwitterAPI認証処理にリダイレクトする
     * @param  SignUpRequest  $request (screen_name, email, password)
     * @return void
     */
    protected function signup(SignUpRequest $request)
    {
        session()->put('screen_name', $request->screen_name);
        session()->put('email', $request->email);
        session()->put('password', Hash::make($request->password));

        // TwitterAPI連携処理にリダイレクト
        return redirect('oauth');
    }
}
