<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
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
     * @param Request $request (status)
     * @return void
     */
    public function index(Request $request)
    {
        $status = $request->session()->get('status');
        return view('index', ['status' => $status]);
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
        Mail::to('cryptotrend@nyankormotti.com')->send(new ContactMail($fromEmail, $comment));
        // リダイレクト後、画面に表示させるメッセージをセッションに格納
        $request->session()->flash('status', 'お問い合わせメールを送信しました。');
        return redirect()->action('IndexController@index');
    }
}
