<template>
    <section class="main__base">
        <section class="p-trend">
            <div class="p-trend__top">
                <h2 class="p-trend__top__title">Crypto Ranking</h2>
                <span class="p-trend__top__border"></span>
            </div>
            <div id="trend_main_template" class="p-trend__main">
                <table is="TrendMain"
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
                </table>
                <div class="p-note">
                    <p class="p-note__describe">※銘柄名をクリックするとtwitter検索画面が表示されます。</p>
                    <p class="p-note__describe">※取引価格は24時間当たりの価格を示しています。</p>
                </div>
            </div>
        </section>
        <section id="trend_main_template" class="p-sidebar">
            <div class="p-sidebar__top">
                <h2 class="p-sidebar__top__title">Search</h2>
                <span class="p-sidebar__top__border"></span>
            </div>
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
        </section>

    </section>
</template>

<script>

import TrendMain from './TrendMainComponent.vue'
import TrendSearch from './TrendSearchComponent.vue'

export default {
    components: {
        TrendMain,
        TrendSearch
    },
    data: function() {
        return {
            period: '',
            currency1: false,
            currency2: false,
            currency3: false,
            currency4: false,
            currency5: false,
            currency6: false,
            currency7: false,
            currency8: false,
            currency9: false,
            currency10: false
        }
    },
    methods: {
        searchHourPeriod:function() {
            this.period = 1;
        },
        searchDatePeriod:function() {
            this.period = 2;
        },
        searchWeekPeriod:function() {
            this.period = 3;
        },
        changeBTC:function() {
            this.currency1 = !this.currency1;
        },
        changeETH:function() {
            this.currency2 = !this.currency2;
        },
        changeETC:function() {
            this.currency3 = !this.currency3;
        },
        changeLSK:function() {
            this.currency4 = !this.currency4;
        },
        changeFCT:function() {
            this.currency5 = !this.currency5;
        },
        changeXRP:function() {
            this.currency6 = !this.currency6;
        },
        changeXEM:function() {
            this.currency7 = !this.currency7;
        },
        changeLTC:function() {
            this.currency8 = !this.currency8;
        },
        changeBCH:function() {
            this.currency9 = !this.currency9;
        },
        changeMONA:function() {
            this.currency10 = !this.currency10;
        },
    },
    created() {
        this.searchHourPeriod()
    },
}
</script>
