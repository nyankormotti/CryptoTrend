<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Mail\ChangePasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ContactAfterRequest;
use App\Http\Requests\ChangePasswordRequest;

/**
 * マイページコントローラ
 */
class MypageController extends Controller
{
    /**
     * マイページ画面表示
     * @return void
     */
    public function index() {
        return view('mypage');
    }

    /**
     * メールアドレス変更処理
     * @param ChangeEmailRequest $request (email)
     * @return void
     */
    public function changeEmail(ChangeEmailRequest $request)
    {
        // usersテーブルに新しいメールアドレスを更新
        $user = User::where('id', Auth::id())->first();
        $user->email = $request->email;
        $user->save();
        // リダイレクト後、画面に表示させるメッセージをセッションに格納
        $request->session()->flash('status', 'メールアドレスを変更しました。');
        return redirect()->action('TrendController@index');
    }

    /**
     * パスワード変更処理
     * @param ChangePasswordRequest $request (password)
     * @return void
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        // usersテーブルに新しいパスワードを更新
        $user = User::where('id', Auth::id())->first();
        $user->password = Hash::make($request->password);
        $user->save();
        // パスワード変更をメールで通知
        Mail::to($user->email)->send(new ChangePasswordMail($request->password));
        // リダイレクト後、画面に表示させるメッセージをセッションに格納
        $request->session()->flash('status', 'パスワードを変更しました。');
        return redirect()->action('TrendController@index');
    }

    /**
     * お問い合わせ処理
     * @param ContactAfterRequest $request (comment)
     * @return void
     */
    public function contact(ContactAfterRequest $request)
    {
        $user = User::where('id', Auth::id())->first();
        $fromEmail = $user->email;
        $comment = $request->comment;
        // お問い合わせ内容をメール送信
        Mail::to('cryptotrend@nyankormotti.com')->send(new ContactMail($fromEmail, $comment));
        // リダイレクト後、画面に表示させるメッセージをセッションに格納
        $request->session()->flash('status', 'お問い合わせメールを送信しました。');
        return redirect()->action('TrendController@index', $request);
    }

    /**
     * 退会処理
     * @param Request $request
     * @return void
     */
    public function withdraw(Request $request)
    {
        // usersテーブルの該当ユーザーを削除(削除フラグをONにする)
        $user = User::where('id', Auth::id())->first();
        $user->delete_flg = true;
        $user->save();
        //セッションクリア
        //アクセストークンだけsessionから破棄
        session()->forget('oauth_token');
        session()->forget('oauth_token_secret');
        // ログアウト処理
        Auth::logout();

        return redirect()->action('WithdrawDoneController@index', $request);
    }

}
