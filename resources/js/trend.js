import Vue from 'vue'
import axios from 'axios'
Vue.prototype.$axios = axios;
Vue.config.devtools = true;

//トレンド一覧画面のVue

// Components
import Trend from './components/TrendComponent.vue'

new Vue({
    el: '#trend_template',
    components: {
        trend: Trend
    }
})