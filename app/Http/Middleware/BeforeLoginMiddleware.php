<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\CommonFunctions;
use Illuminate\Support\Facades\Auth;

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
            // 共通関数のTwitterアカウント照合処理を実行
            $commonFunc = new CommonFunctions;
            if ($commonFunc->checkAccount()) {
                return redirect()->action('ChangeTwitterAccountController@index');
            }
            return redirect()->action('TrendController@index');
        }
        return $next($request);
    }
}
