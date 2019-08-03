<?php

namespace App\Libraries;

class CommonFunctions
{

    // Google News 取得関数
    function get_news()
    {
        set_time_limit(90);

        // 配列
        $list = array();

        //---- キーワード検索したいときのベースURL 
        $API_BASE_URL = "https://news.google.com/news?hl=ja&ned=ja&ie=UTF-8&oe=UTF-8&output=atom&q=";

        //----　キーワードの文字コード変更
        $query = urlencode(mb_convert_encoding("仮想通貨", "UTF-8", "auto"));

        //---- APIへのリクエストURL生成
        $api_url = $API_BASE_URL . $query;

        //---- APIにアクセス、結果をsimplexmlに格納
        $contents = file_get_contents($api_url);
        $xml = simplexml_load_string($contents);

        //記事エントリを取り出す
        $data = $xml->entry;

        //記事のタイトルとURLを取り出して配列に格納
        for ($i = 0; $i < count($data); $i++) {

            $list[$i]['title'] = mb_convert_encoding($data[$i]->title, "UTF-8", "auto");
            $url_split =  explode("=", (string) $data[$i]->link->attributes()->href);
            $list[$i]['updated'] = mb_convert_encoding($data[$i]->updated, "UTF-8", "auto");
            $list[$i]['updated'] = substr($list[$i]['updated'],0,10).' '. substr($list[$i]['updated'], 11, 8);
            $list[$i]['url'] = end($url_split);
        }

        //配列を出力
        return $list;
    }

    // 取引価格を取得する関数
    function coincheck()
    {
        $strUrl = "https://coincheck.com/api/ticker";
        $file = file_get_contents($strUrl);
        $file = mb_convert_encoding($file, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

        $asdf = json_decode($file, true);
        return $asdf;
    }

    
}