<template>
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
            <tr v-for="trend in trends" v-bind:key="trend.rank" class="p-table__tbody__tr">
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
</template>

<script>
export default {
  data: function() {
    return {
      trends: []
    }
  },
  methods: {
      fetchTrend:function() {
           this.$axios.get('/trend/get').then((res)=>{
            this.trends = res.data
      })
      }
  },
  created() {
    this.fetchTrend()
  },
}
</script>


