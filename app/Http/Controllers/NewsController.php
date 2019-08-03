<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Libraries\CommonFunctions;

class NewsController extends Controller
{
    public function index() {
        $commonFunc = new CommonFunctions;
        $news_list = $commonFunc->get_news();
        return view('news', compact('news_list'));
        
    }
}
