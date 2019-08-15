<?php

namespace App\Http\Controllers;

/**
 * 退会完了画面コントローラ
 * 退会完了画面へ遷移するアクションを実装
 */
class WithdrawDoneController extends Controller
{
    /**
     * 退会完了画面表示
     * @return void
     */
    public function index()
    {
        return view('withdrawDone');
    }
}
