<template>
    <section class="p-trend">
        <div class="p-trend__top">
            <div class="p-trend__top__area">
                <h2 class="p-trend__top__area__title">Crypto Ranking</h2>
                <p class="p-trend__top__area__page">更新：{{updated | moment}}</p>
            </div>
            <span class="p-trend__top__border"></span>
        </div>
        <div id="trend_main_template" class="p-trend__main">
            <table class="p-table">
                <thead class="p-table__thead">
                    <tr class="p-table__thead__tr">
                        <th class="p-table__thead__tr__th">順位</th>
                        <th class="p-table__thead__tr__th">銘柄</th>
                        <th class="p-table__thead__tr__th">ツイート数</th>
                        <th class="p-table__thead__tr__th">最高取引
                            <br>価格(JPY)</th>
                        <th class="p-table__thead__tr__th">最安取引
                            <br>価格(JPY)</th>
                    </tr>
                </thead>
                <tbody class="p-table__tbody">
                    <tr v-for="trend in searchTrend" :key="trend.rank" class="p-table__tbody__tr">
                        <td class="p-table__tbody__tr__td">{{trend.rank}}位</td>
                        <td class="p-table__tbody__tr__td">
                            <a v-if="trend.rank == 1" :href="'https://twitter.com/search?q=' + trend.currency.currency_name + '&src=recent_search_click'" class="p-table__tbody__tr__td__link p-table__tbody__tr__td__link--gold">{{trend.currency.currency_name}}</a>
                            <a v-else-if="trend.rank == 2" :href="'https://twitter.com/search?q=' + trend.currency.currency_name + '&src=recent_search_click'" class="p-table__tbody__tr__td__link p-table__tbody__tr__td__link--silver">{{trend.currency.currency_name}}</a>
                            <a v-else-if="trend.rank == 3" :href="'https://twitter.com/search?q=' + trend.currency.currency_name + '&src=recent_search_click'" class="p-table__tbody__tr__td__link p-table__tbody__tr__td__link--blonze">{{trend.currency.currency_name}}</a>
                            <a v-else :href="'https://twitter.com/search?q=' + trend.currency.currency_name + '&src=recent_search_click'" class="p-table__tbody__tr__td__link">{{trend.currency.currency_name}}</a>
                        </td>
                        <td class="p-table__tbody__tr__td">{{trend.tweet_count}}</td>
                        <td v-if="trend.currency.max_price == null" class="p-table__tbody__tr__td">不明</td>
                        <td v-else class="p-table__tbody__tr__td">{{trend.currency.max_price}}</td>
                        <td v-if="trend.currency.min_price == null"  class="p-table__tbody__tr__td">不明</td>
                        <td v-else class="p-table__tbody__tr__td">{{trend.currency.min_price}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="p-note">
                <p class="p-note__describe">※銘柄名をクリックするとtwitter検索画面が表示されます。</p>
                <p class="p-note__describe">※取引価格は24時間当たりの価格を示しています。</p>
            </div>
        </div>
    </section>
</template>

<script>
import moment from 'moment';
export default {
    props:[
        'period', //期間(1:1時間, 2:1日, 3:１週間)
        'currency1', // 銘柄検索フラグ ($BTC)
        'currency2', // 銘柄検索フラグ ($ETH)
        'currency3', // 銘柄検索フラグ ($ETC)
        'currency4', // 銘柄検索フラグ ($LSK)
        'currency5', // 銘柄検索フラグ ($FCT)
        'currency6', // 銘柄検索フラグ ($XRP)
        'currency7', // 銘柄検索フラグ ($XEM)
        'currency8', // 銘柄検索フラグ ($LTC)
        'currency9', // 銘柄検索フラグ ($BCH)
        'currency10' // 銘柄検索フラグ ($MONA)
    ],
    data: function() {
    return {
        trends: [], //トレンド情報
        updated: '' //トレンド情報の最終更新日時
        }
    },
    methods: {
        // トレンド情報を取得
        fetchTrend:function() {
            this.$axios.post('/trend/get',{
                period_id: this.period //期間(1:1時間, 2:1日, 3:１週間)
            }).then((res)=>{
                this.trends = res.data
            }).catch(err => {
                alert('問題が発生しました。しばらく経ってからお試しください。')
            });
        },
        // トレンド情報の最終更新日時を取得
        getUpdatedTime: function() {
            this.$axios.post('/trend/getUpdated',{
                period_id: this.period //期間(1:1時間, 2:1日, 3:１週間)
            }).then((res)=>{
                this.updated = res.data
            }).catch(err => {
                alert('問題が発生しました。しばらく経ってからお試しください。')
            });
        },
        // 銘柄検索結果を表示する配列に格納
        searchCheck: function(i, id, trend, searchTrends) {
            if(id == trend.currency.id) {
                searchTrends.push(trend);
            }
        }
    },
    filters: {
        // トレンド情報の最終更新日時のフォーマットを変換する。
        moment: function (date) {
            moment.locale("ja");
            return moment(date).format('YYYY年MM月DD日 HH時mm分');
        }
    },
    computed:{
        // 銘柄検索処理
        searchTrend: function() {
            if(!this.currency1
                && !this.currency2
                && !this.currency3
                && !this.currency4
                && !this.currency5
                && !this.currency6
                && !this.currency7
                && !this.currency8
                && !this.currency9
                && !this.currency10) {
                    // 画面上の全ての銘柄を選択していない場合、全ての銘柄を表示する。
                return this.trends
            } else {
                // 銘柄が個別で選択されている場合
                let searchTrends = [];
                for (let i in this.trends) {
                    // DBから取得したトレンド情報
                    let trend = this.trends[i];
                    let id = 0;
                    // $BTCを選択している場合、$BTCを表示する
                    if(this.currency1) {
                        this.searchCheck(i,1,trend,searchTrends)
                    }
                    // $ETHを選択している場合、$ETHを表示する
                    if(this.currency2) {
                        this.searchCheck(i,2,trend,searchTrends)
                    }
                    // $ETCを選択している場合、$ETCを表示する
                    if(this.currency3) {
                        this.searchCheck(i,3,trend,searchTrends)
                    }
                    // $LSKを選択している場合、$LSKを表示する
                    if(this.currency4) {
                        this.searchCheck(i,4,trend,searchTrends)
                    }
                    // $FTCを選択している場合、$FTBを表示する
                    if(this.currency5) {
                        this.searchCheck(i,5,trend,searchTrends)
                    }
                    // $XRPを選択している場合、$XRPを表示する
                    if(this.currency6) {
                        this.searchCheck(i,6,trend,searchTrends)
                    }
                    // $XEMを選択している場合、$XEMを表示する
                    if(this.currency7) {
                        this.searchCheck(i,7,trend,searchTrends)
                    }
                    // $LTCを選択している場合、$LTCを表示する
                    if(this.currency8) {
                        this.searchCheck(i,8,trend,searchTrends)
                    }
                    // $BCHを選択している場合、$BCHを表示する
                    if(this.currency9) {
                        this.searchCheck(i,9,trend,searchTrends)
                    }
                    // $MONAを選択している場合、$MONAを表示する
                    if(this.currency10) {
                        this.searchCheck(i,10,trend,searchTrends)
                    }
                }
                return searchTrends;
            }
        }
    },
    watch: {
        // 表示形式(1時間、1日、１週間)が変わった際に、トレンド情報とその最終更新日時を取得する。
        period: function() {
            this.fetchTrend()
            this.getUpdatedTime()
        }
    },
    created() {
         // 初期表示時に、トレンド情報とその最終更新日時を取得する。
        this.fetchTrend()
        this.getUpdatedTime()
    },
}
</script>


