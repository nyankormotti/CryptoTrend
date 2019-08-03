import Vue from 'vue'
import axios from 'axios' 
Vue.prototype.$axios = axios;


// Components
import News from './components/NewsComponent.vue'

new Vue({
    el: '#news_template',
    components: {
        news: News
    }
})