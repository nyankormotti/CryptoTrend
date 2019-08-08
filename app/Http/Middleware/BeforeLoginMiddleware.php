<?php

namespace App\Http\Middleware;

use Closure;
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
        \Log::info('ミドルウェアだよ');
        if (Auth::check()) {
            \Log::info('ミドルウェア');
            return redirect()->action('TrendController@index');
        }
        return $next($request);
    }
}
