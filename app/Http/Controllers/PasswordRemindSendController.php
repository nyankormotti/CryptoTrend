<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\PasswordRemindSendMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PasswordRemindSendRequest;

class PasswordRemindSendController extends Controller
{
    public function index()
    {
        return view('passwordRemindSend');
    }

    public function send(PasswordRemindSendRequest $request)
    {
        $toEmail = $request->email;
        $auth_key = '';
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        for ($i = 0; $i < 8; $i++) {
            $auth_key .= $chars[mt_rand(0, 61)];
        }
        Mail::to($toEmail)->send(new PasswordRemindSendMail($auth_key));
        $request->session()->flash('status', '認証キーを発行しました。');
        $request->session()->put('toEmail', $toEmail);
        session(['auth_key' => $auth_key]);
        return redirect()->action('PasswordRemindRecieveController@index', $request);
    }

}
