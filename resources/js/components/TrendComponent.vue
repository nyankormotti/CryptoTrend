<template>
    <section class="main__base">
        <!-- トレンドランキング表示領域 -->
        <section 
            is="TrendMain"
            :period="period"
            :currency1="currency1"
            :currency2="currency2"
            :currency3="currency3"
            :currency4="currency4"
            :currency5="currency5"
            :currency6="currency6"
            :currency7="currency7"
            :currency8="currency8"
            :currency9="currency9"
            :currency10="currency10">
        </section>

        <!-- 検索フォーム -->
        <TrendSearch
            :period="period"
            :currency1="currency1"
            :currency2="currency2"
            :currency3="currency3"
            :currency4="currency4"
            :currency5="currency5"
            :currency6="currency6"
            :currency7="currency7"
            :currency8="currency8"
            :currency9="currency9"
            :currency10="currency10"
            @child-h-period="searchHourPeriod"
            @child-d-period="searchDatePeriod"
            @child-w-period="searchWeekPeriod"
            @child-BTC="changeBTC"
            @child-ETH="changeETH"
            @child-ETC="changeETC"
            @child-LSK="changeLSK"
            @child-FCT="changeFCT"
            @child-XRP="changeXRP"
            @child-XEM="changeXEM"
            @child-LTC="changeLTC"
            @child-BCH="changeBCH"
            @child-MONA="changeMONA"
        ></TrendSearch>

        <!-- 検索フォーム(レスポンシブ) -->
        <TrendSearchSp
            :period="period"
            :currency1="currency1"
            :currency2="currency2"
            :currency3="currency3"
            :currency4="currency4"
            :currency5="currency5"
            :currency6="currency6"
            :currency7="currency7"
            :currency8="currency8"
            :currency9="currency9"
            :currency10="currency10"
            @child-h-period="searchHourPeriod"
            @child-d-period="searchDatePeriod"
            @child-w-period="searchWeekPeriod"
            @child-BTC="changeBTC"
            @child-ETH="changeETH"
            @child-ETC="changeETC"
            @child-LSK="changeLSK"
            @child-FCT="changeFCT"
            @child-XRP="changeXRP"
            @child-XEM="changeXEM"
            @child-LTC="changeLTC"
            @child-BCH="changeBCH"
            @child-MONA="changeMONA"
        ></TrendSearchSp>
    </section>
</template>

<script>

import TrendMain from './TrendMainComponent.vue' //トレンドランキング表示領域
import TrendSearch from './TrendSearchComponent.vue' //検索フォーム
import TrendSearchSp from './TrendSearchSpComponent.vue' //検索フォーム

export default {
    components: {
        TrendMain,
        TrendSearch,
        TrendSearchSp
    },
    data: function() {
        return {
            period: 1, //期間(1:1時間, 2:1日, 3:１週間)
            currency1: false, // 銘柄検索フラグ ($BTC)
            currency2: false, // 銘柄検索フラグ ($ETH)
            currency3: false, // 銘柄検索フラグ ($ETC)
            currency4: false, // 銘柄検索フラグ ($LSK)
            currency5: false, // 銘柄検索フラグ ($FCT)
            currency6: false, // 銘柄検索フラグ ($XRP)
            currency7: false, // 銘柄検索フラグ ($XEM)
            currency8: false, // 銘柄検索フラグ ($LTC)
            currency9: false, // 銘柄検索フラグ ($BCH)
            currency10: false // 銘柄検索フラグ ($MONA)
        }
    },
    methods: {
        // 期間：「1時間」を選択した場合
        searchHourPeriod:function() {
            // 期間(1:1時間)を更新
            this.period = 1;
        },
        // 期間：「1日」を選択した場合
        searchDatePeriod:function() {
            // 期間(2:1日)を更新
            this.period = 2;
        },
        // 期間：「1週間」を選択した場合
        searchWeekPeriod:function() {
            // 期間(3:1週間)を更新
            this.period = 3;
        },
        // 銘柄：「$BTC」を選択した場合
        changeBTC:function() {
            // 銘柄検索フラグ ($BTC)の値を反転
            this.currency1 = !this.currency1;
        },
        // 銘柄：「$ETH」を選択した場合
        changeETH:function() {
            // 銘柄検索フラグ ($ETH)の値を反転
            this.currency2 = !this.currency2;
        },
        // 銘柄：「$ETC」を選択した場合
        changeETC:function() {
            // 銘柄検索フラグ ($ETC)の値を反転
            this.currency3 = !this.currency3;
        },
        // 銘柄：「$LSK」を選択した場合
        changeLSK:function() {
            // 銘柄検索フラグ ($LSK)の値を反転
            this.currency4 = !this.currency4;
        },
        // 銘柄：「$FCT」を選択した場合
        changeFCT:function() {
            // 銘柄検索フラグ ($FCT)の値を反転
            this.currency5 = !this.currency5;
        },
        // 銘柄：「$XRP」を選択した場合
        changeXRP:function() {
            // 銘柄検索フラグ ($XRP)の値を反転
            this.currency6 = !this.currency6;
        },
        // 銘柄：「$XEM」を選択した場合
        changeXEM:function() {
            // 銘柄検索フラグ ($XEM)の値を反転
            this.currency7 = !this.currency7;
        },
        // 銘柄：「$LTC」を選択した場合
        changeLTC:function() {
            // 銘柄検索フラグ ($LTC)の値を反転
            this.currency8 = !this.currency8;
        },
        // 銘柄：「$BCH」を選択した場合
        changeBCH:function() {
            // 銘柄検索フラグ ($BCH)の値を反転
            this.currency9 = !this.currency9;
        },
        // 銘柄：「$MONA」を選択した場合
        changeMONA:function() {
            // 銘柄検索フラグ ($MONA)の値を反転
            this.currency10 = !this.currency10;
        },
    }
}
</script>
