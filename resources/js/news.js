import Vue from 'vue'

// Components
import News from './components/NewsComponent.vue'

new Vue({
    el: '#test',
    components: {
        news: News
    }
})