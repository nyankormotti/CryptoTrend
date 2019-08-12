import Vue from 'vue'
import axios from 'axios'
Vue.prototype.$axios = axios;
Vue.config.devtools = true;

// Components
import Account from './components/AccountComponent.vue'

new Vue({
    el: '#account_template',
    components: {
        account: Account
    }
})
