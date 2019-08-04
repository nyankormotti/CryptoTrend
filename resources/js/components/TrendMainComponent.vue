<template>
    <section class="p-trend">
        <div class="p-trend__top">
            <div class="p-trend__top__area">
                <h2 class="p-trend__top__area__title">Crypto Ranking</h2>
                <p class="p-trend__top__area__page">更新：{{searchTrend[0].updated_at | moment}}</p>
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
        'period',
        'currency1',
        'currency2',
        'currency3',
        'currency4',
        'currency5',
        'currency6',
        'currency7',
        'currency8',
        'currency9',
        'currency10'
    ],
    data: function() {
    return {
        trends: [],
        ranking: 0
        }
    },
    methods: {
        fetchTrend:function() {
            this.$axios.post('/trend/get',{
                period_id: this.period
            }).then((res)=>{
                this.trends = res.data
            })
        },
        searchCheck: function(i,id,trend,trends) {
            if(id == trend.currency.id) {
                trends.push(trend);
            }
        }
    },
    filters: {
        moment: function (date) {
            moment.locale("ja");
            return moment(date).format('YYYY年MM月DD日 HH時mm分');
        }
    },
    computed:{
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
                return this.trends
            } else {
                let trends = [];
                for (let i in this.trends) {
                    let trend = this.trends[i];
                    let id = 0;
                    if(this.currency1) {
                        this.searchCheck(i,1,trend,trends)
                    }
                    if(this.currency2) {
                        this.searchCheck(i,2,trend,trends)
                    }
                    if(this.currency3) {
                        this.searchCheck(i,3,trend,trends)
                    }
                    if(this.currency4) {
                        this.searchCheck(i,4,trend,trends)
                    }
                    if(this.currency5) {
                        this.searchCheck(i,5,trend,trends)
                    }
                    if(this.currency6) {
                        this.searchCheck(i,6,trend,trends)
                    }
                    if(this.currency7) {
                        this.searchCheck(i,7,trend,trends)
                    }
                    if(this.currency8) {
                        this.searchCheck(i,8,trend,trends)
                    }
                    if(this.currency9) {
                        this.searchCheck(i,9,trend,trends)
                    }
                    if(this.currency10) {
                        this.searchCheck(i,10,trend,trends)
                    }
                }
                return trends;
            }
        }
    },
    watch: {
        period: function() {
            this.fetchTrend();
        }
    },
    created() {
        this.fetchTrend()
    },
}
</script>


