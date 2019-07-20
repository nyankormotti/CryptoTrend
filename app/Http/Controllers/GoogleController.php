<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Libraries\CommonFunctions;

class GoogleController extends Controller
{
    public function sample() {
        $commonFunc = new CommonFunctions;
        $news_list = $commonFunc->get_news();
        // $data = $cf->is_foo('foo');
        // var_dump($news_list);
        // exit;
        return view('google', compact('news_list'));
        
    }
}
