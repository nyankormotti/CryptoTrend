<?php

namespace App\Http\Controllers;

use App\User;
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

}
