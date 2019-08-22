<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;

/**
 * お問い合わせメール送信
 */
class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $fromEmail;
    protected $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fromEmail, $comment)
    {
        // 引数で受け取ったデータを変数にセット
        $this->fromEmail = $fromEmail;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(Auth::check()){
            return $this
                ->from($this->fromEmail) // 送信元
                ->subject('【お問い合わせ】ログインユーザー') // メールタイトル
                ->view('mail.contactMail') // どのテンプレートを呼び出すか
                ->with(['comment' => $this->comment]); // withオプションでセットしたデータをテンプレートへ受け渡す
        } else {
            return $this
                ->from($this->fromEmail) // 送信元
                ->subject('【お問い合わせ】未ログインユーザー') // メールタイトル
                ->view('mail.contactMail') // どのテンプレートを呼び出すか
                ->with(['comment' => $this->comment]); // withオプションでセットしたデータをテンプレートへ受け渡す
        }
    }
}
