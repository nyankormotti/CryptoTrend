<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    /**
     * マイページ画面表示
     * @return void
     */
    public function index() {
        return view('mypage');
    }
}
