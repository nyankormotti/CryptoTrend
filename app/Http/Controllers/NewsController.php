<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Libraries\CommonFunctions;

class NewsController extends Controller
{
    /**
     * 仮想通貨関連ニュース画面表示
     * @return array $news_list ニュース一覧
     */
    public function index() {
        // 仮想通貨関連ニュース 取得関数を呼び出す
        // Google newsより「仮想通貨」のキーワードが含まれる最新の100件を取得
        $commonFunc = new CommonFunctions;
        $news_list = $commonFunc->getNews();

        return view('news', compact('news_list'));
    }
}
