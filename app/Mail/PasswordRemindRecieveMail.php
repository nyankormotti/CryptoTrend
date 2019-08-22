<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * パスワードリマインダー(パスワード再発行)
 */
class PasswordRemindRecieveMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $password;

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
            ->from('cryptotrend@nyankormotti.com') // 送信元
            ->subject('【パスワード再発行】再発行パスワード送付') // メールタイトル
            ->view('mail.passwordRemindRecieveMail') // どのテンプレートを呼び出すか
            ->with(['password' => $this->password]); // withオプションでセットしたデータをテンプレートへ受け渡す
    }
}
