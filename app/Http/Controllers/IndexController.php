<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactBeforeRequest;

class IndexController extends Controller
{
    /**
     * トップページ表示
     * @return void
     */
    public function index(Request $request){

        return view('index');
    }

    /**
     * お問い合わせ処理
     * @param $request リクエストパラメータ
     * @return void
     */
    public function contact(ContactBeforeRequest $request)
    {
        $fromEmail = $request->email;
        $comment = $request->comment;
        Mail::to('info@info.com')->send(new ContactMail($fromEmail, $comment));
        $request->session()->flash('status', 'お問い合わせメールを送信しました。');
        return redirect()->action('IndexController@index', $request);
    }
}
