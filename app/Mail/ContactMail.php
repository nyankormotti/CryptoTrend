<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    public function __construct($name, $fromEmail, $comment)
    {
        // 引数で受け取ったデータを変数にセット
        $this->name = $name;
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
        return $this
            ->from($this->fromEmail) // 送信元
            ->subject('【お問い合わせ】未ログインユーザー') // メールタイトル
            ->view('mail.contactMail') // どのテンプレートを呼び出すか
            ->with(['name' => $this->name, 'comment' => $this->comment]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
