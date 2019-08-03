<template>
    <div>
        <div v-for="news in news_list" v-bind:key="news.id" class="p-news__content__article">
            <div class="p-news__content__article__area">
                <a href="" class="p-news__content__article__area__describe" target=”_blank” rel=”noopener”>{{news.title}}</a>
            </div>
            <div class="p-news__content__article__area">
                <p class="p-news__content__article__area__upday">更新日：{{news.updated}}</p>
            </div>
        </div>
        <div class="p-news__content__dummy"></div>
    </div>
</template>

<script>
export default {
  data: function() {
    return {
      news_list: []
    }
  },
  methods: {
      fetchNews:function() {
           this.$axios.get('/news/get').then((res)=>{
            this.news_list = res.data
      })
      }
  },
  created() {
    this.fetchNews()
  },
}
</script>


