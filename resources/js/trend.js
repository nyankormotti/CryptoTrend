import Vue from 'vue'
import axios from 'axios'
Vue.prototype.$axios = axios;


// Components
import Trend from './components/TrendComponent.vue'

new Vue({
    el: '#trend_template',
    components: {
        trend: Trend
    }
})