<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * ログイン前に使用できる画面に遷移した際に処理を実行するミドルウェア
 * ログイン情報がセッションにあれば、トレンド一覧画面のコントローラにリダイレクト
 * なければ、希望の画面のコントローラに遷移する
 */
class BeforeLoginMiddleware
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
        if (Auth::check()) {
            return redirect()->action('TrendController@index');
        }
        return $next($request);
    }
}
