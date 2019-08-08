<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * トップページ表示
     * @return void
     */
    public function index(){

        return view('index');
    }
}
