<?php

namespace App\Http\Controllers;

use App\Mail\PasswordRemindSendMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PasswordRemindSendRequest;

/**
 * パスワードリマインダーコントローラ(自身のメールアドレスを入力し、認証キーを発行する)
 */
class PasswordRemindSendController extends Controller
{
    /**
     * パスワードリマインダー画面表示 (認証キー)
     * @return void
     */
    public function index()
    {
        return view('passwordRemindSend');
    }

    /**
     * 認証キー送信処理(入力したメールアドレス宛に送信)
     * @param PasswordRemindSendRequest $request (email)
     * @return void
     */
    public function send(PasswordRemindSendRequest $request)
    {
        $toEmail = $request->email;
        // 認証キー作成
        $auth_key = '';
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        for ($i = 0; $i < 8; $i++) {
            $auth_key .= $chars[mt_rand(0, 61)];
        }
        // メール送信処理
        Mail::to($toEmail)->send(new PasswordRemindSendMail($auth_key));
        // リダイレクト後、画面に表示させるメッセージをセッションに格納
        $request->session()->flash('status', '認証キーを発行しました。');
        // メールアドレスをセッションに格納
        $request->session()->put('toEmail', $toEmail);
        // 認証キーをセッションに格納
        session(['auth_key' => $auth_key]);
        return redirect()->action('PasswordRemindRecieveController@index', $request);
    }

}
