<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Mail\ChangePasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
     * メールアドレス変更
     * @param $request リクエストパラメータ
     * @return void
     */
    public function changeEmail(Request $request)
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $user->email = $request->email;
        $user->save();
        $request->session()->flash('status', 'メールアドレスを変更しました。');
        return redirect()->action('TrendController@index');
    }

    /**
     * パスワード変更処理
     * @param $request リクエストパラメータ
     * @return void
     */
    public function changePassword(Request $request)
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        Mail::to($user->email)->send(new ChangePasswordMail($request->password));
        $request->session()->flash('status', 'パスワードを変更しました。');
        return redirect()->action('TrendController@index');
    }

    /**
     * お問い合わせ処理
     * @param $request リクエストパラメータ
     * @return void
     */
    public function contact(Request $request)
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $fromEmail = $user->email;
        $comment = $request->comment;
        Mail::to('info@info.com')->send(new ContactMail($fromEmail, $comment));
        $request->session()->flash('status', 'お問い合わせメールを送信しました。');
        return redirect()->action('TrendController@index', $request);
    }

}
