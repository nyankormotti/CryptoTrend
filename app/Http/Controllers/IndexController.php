<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactBeforeRequest;

/**
 * トップページコントローラ
 * 
 * トップページを表示する
 * お問い合わせ処理を実施する
 */
class IndexController extends Controller
{
    /**
     * トップページ表示
     * @return void
     */
    public function index()
    {
        return view('index');
    }

    /**
     * お問い合わせ処理
     * @param ContactBeforeRequest $request (email, comment)
     * @return void
     */
    public function contact(ContactBeforeRequest $request)
    {
        $fromEmail = $request->email;
        $comment = $request->comment;
        // メール送信処理
        Mail::to('info@info.com')->send(new ContactMail($fromEmail, $comment));
        $request->session()->flash('status', 'お問い合わせメールを送信しました。');
        return redirect()->action('IndexController@index', $request);
    }
}
