<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExceptionController extends Controller
{
    /**
     * 例外発生画面表示
     */
    public function index()
    {
        return view('exception');
    }
}

