<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{

    public function index(){

        if (!empty(session()->get('message'))) {
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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function signup(SignUpRequest $request)
    {
        session()->put('screen_name', $request->screen_name);
        session()->put('email', $request->email);
        session()->put('password', Hash::make($request->password));

        return redirect('oauth');
    }
}
