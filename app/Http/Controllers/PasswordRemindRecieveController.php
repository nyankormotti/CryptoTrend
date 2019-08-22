<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRemindRecieveMail;
use App\Http\Requests\PasswordRemindRecieveRequest;

/**
 * パスワードリマインダーコントローラ(認証キーを入力し、パスワードを再発行する)
 */
class PasswordRemindRecieveController extends Controller
{
    /**
     * パスワードリマインダー画面表示(パスワード再発行)
     * @return void
     */
    public function index(Request $request)
    {
        $status = $request->session()->get('status');
        return view('passwordRemindRecieve', ['status' => $status]);
    }

    /**
     * パスワード再発行処理
     * @param PasswordRemindRecieveRequest $request (session(toEmail), auth_key)
     * @return void
     */
    public function send(PasswordRemindRecieveRequest $request)
    {
        // リクエスト情報、セッション情報を取得
        $toEmail = $request->session()->get('toEmail');
        $auth_key = session()->get('auth_key');
        session()->forget('toEmail');
        session()->forget('auth_key');
        // 認証キー照合処理
        if ($auth_key !== $request->auth_key) {
            return view('passwordRemindRecieve');
        }
        // 再発行パスワード作成
        $password = '';
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        for ($i = 0; $i < 8; $i++) {
            $password .= $chars[mt_rand(0, 61)];
        }
        //usersテーブルに再発行したパスワードを更新
        $user = User::where('email', $toEmail)->where('delete_flg', false)->first();
        $user->password = Hash::make($password);
        $user->save();
        // メール送信処理
        Mail::to($toEmail)->send(new PasswordRemindRecieveMail($password));
        // リダイレクト後、画面に表示させるメッセージをセッションに格納
        $request->session()->flash('status', 'パスワードを再発行しました。');
        return redirect()->action('IndexController@index', $request);
    }
}
