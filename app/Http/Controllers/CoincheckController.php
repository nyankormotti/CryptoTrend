<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Libraries\CommonFunctions;

class CoincheckController extends Controller
{
    public function index()
    {
        $commonFunc = new CommonFunctions;
        $rate = $commonFunc->coincheck();
        // var_dump($rate);exit;

        return view('coincheck', compact('rate'));
    }
}
