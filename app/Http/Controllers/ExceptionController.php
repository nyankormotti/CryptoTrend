<?php

namespace App\Http\Controllers;

class ExceptionController extends Controller
{
    /**
     * 例外発生画面表示
     * 
     * @return void
     */
    public function index()
    {
        return view('exception');
    }
}

