<template>
    <section class="main__base">
        <section class="p-trend">
            <div class="p-trend__top">
                <h2 class="p-trend__top__title">Crypto Ranking</h2>
                <span class="p-trend__top__border"></span>
            </div>
            <div id="trend_main_template" class="p-trend__main">
                <table is="TrendMain"></table>
                <div class="p-note">
                    <p class="p-note__describe">※銘柄名をクリックするとtwitter検索画面が表示されます。</p>
                    <p class="p-note__describe">※取引価格は24時間当たりの価格を示しています。</p>
                </div>
            </div>
        </section>
        <section id="trend_main_template" class="p-sidebar">
            <div class="p-sidebar__top">
                <h2 class="p-sidebar__top__title">Search</h2>
                <span class="p-sidebar__top__border"></span>
            </div>
            <TrendSearch></TrendSearch>
        </section>

    </section>
</template>

<script>

import TrendMain from './TrendMainComponent.vue'
import TrendSearch from './TrendSearchComponent.vue'

export default {
    components: {
        TrendMain,
        TrendSearch
    },
    
}
</script>
