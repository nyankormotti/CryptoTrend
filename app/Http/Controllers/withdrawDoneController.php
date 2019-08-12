<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
