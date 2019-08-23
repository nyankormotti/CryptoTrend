import Vue from 'vue'
import axios from 'axios'
Vue.prototype.$axios = axios;
Vue.config.devtools = true;

// 仮想通貨アカウント一覧画面のVue

// Components
import Account from './components/AccountComponent.vue'

new Vue({
    el: '#account_template',
    components: {
        account: Account
    }
})
