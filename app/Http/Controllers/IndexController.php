<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
     */
    public function contact(Request $request)
    {

        $name = $request->name;
        $fromEmail = $request->email;
        $comment = $request->comment;
        Mail::to('info@info.com')->send(new ContactMail($name, $fromEmail, $comment));
        $request->session()->flash('status', 'お問い合わせメールを送信しました。');
        return redirect()->action('IndexController@index', $request);
    }
}
