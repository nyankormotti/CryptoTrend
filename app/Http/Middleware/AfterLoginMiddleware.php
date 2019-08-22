<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\CommonFunctions;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン後に使用できる画面に遷移した際に処理を実行するミドルウェア
 * ログイン情報がセッションになければ、トップページにリダイレクト
 * あれば、希望の画面のコントローラに遷移する
 * (この際、usersテーブルに登録されたTwitterアカウントの情報がTwitter上のものと違いがないかを確認する。
 * 違いがあれば、Twitterアカウント変更画面へリダイレクト)
 */
class AfterLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            // トップページへリダイレクト
            return redirect()->action('IndexController@index');
        } else {
            // 共通関数のTwitterアカウント照合処理を実行
            $commonFunc = new CommonFunctions;
            if($commonFunc->checkAccount()) {
                return redirect()->action('ChangeTwitterAccountController@index');
            }
        }
        return $next($request);

    }
}
