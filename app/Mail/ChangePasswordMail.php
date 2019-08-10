<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangePasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('info@info.com') // 送信元
            ->subject('【パスワード変更】変更パスワードの連絡') // メールタイトル
            ->view('mail.changePasswordMail') // どのテンプレートを呼び出すか
            ->with(['password' => $this->password]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
