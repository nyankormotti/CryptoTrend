<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordRemindSendMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $auth_key;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($auth_key)
    {
        $this->auth_key = $auth_key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('cryptotrend@nyankormotti.com') // 送信元
            ->subject('【パスワード再発行】認証キー送付') // メールタイトル
            ->view('mail.passwordRemindSendMail') // どのテンプレートを呼び出すか
            ->with(['auth_key' => $this->auth_key]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
