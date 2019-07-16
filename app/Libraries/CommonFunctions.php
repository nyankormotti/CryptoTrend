<?php

namespace App\Libraries;

class CommonFunctions
{
    public static function is_foo($data)
    {
        return ($data == 'foo') ? true : false;
    }


    // Google News 取得関数
    function get_news($max_num)
    {
        set_time_limit(90);

        // 配列
        $list = array();

        //---- キーワード検索したいときのベースURL 
        $API_BASE_URL = "https://news.google.com/news?hl=ja&ned=ja&ie=UTF-8&oe=UTF-8&output=atom&q=";

        //----　キーワードの文字コード変更
        $query = urlencode(mb_convert_encoding("日本", "UTF-8", "auto"));

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
            $list[$i]['url'] = end($url_split);
        }

        //$max_num以上の記事数の場合は切り捨て
        if (count($list) > $max_num) {
            for ($i = 0; $i < $max_num; $i++) {
                $list_gn[$i] = $list[$i];
            }
        } else {
            $list_gn = $list;
        }
        

        //配列を出力
        return $list_gn;
    }

    // 取引価格を取得する関数

    function coincheck()
    {
        // $strUrl = "https://coincheck.com/api/ticker";
        // $file = file_get_contents($strUrl);
        // $file = mb_convert_encoding($file, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

        // $asdf = json_decode($file, true);
        // // var_dump($asdf);  
        // return $asdf;

        // ticker api url

        // zaif api url
        $zaif_api_url  = "https://api.zaif.jp/api/1/ticker/";

        $ZF = array();

        $currency_pairs = array(
            "bch_jpy", "bitcrystals_jpy", "btc_jpy", "cicc_jpy", "eth_jpy","fscc_jpy", "jpyz_jpy","mona_jpy", "ncxc_jpy","pepecash_jpy", "sjcx_jpy", "xcp_jpy", "xem_jpy", "zaif_jpy"
        );

        foreach($currency_pairs as $pairs){
            // urlに通過を結合
            $url = $zaif_api_url. $pairs;
            $file = file_get_contents($url);
            $file = mb_convert_encoding($file, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            array_push($ZF,$pairs, json_decode($file, true));
        }

        return $ZF;
    }

    
}