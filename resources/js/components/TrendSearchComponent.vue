<template>
    <div class="p-sidebar__area">
        <div class="p-sidebar__period">
            <h3 class="p-sidebar__period__title">期間</h3>
            <div class="p-sidebar__period__area">
                <div v-if="period == 1" class="c-action-btn c-action-btn--blue">1時間</div>
                <div v-else class="c-action-btn" @click="hourPeriod">1時間</div>
                <div v-if="period == 2" class="c-action-btn c-action-btn--blue">1日間</div>
                <div v-else class="c-action-btn" @click="datePeriod">1日間</div>
                <div v-if="period == 3" class="c-action-btn c-action-btn--blue">1週間</div>
                <div v-else class="c-action-btn" @click="weekPeriod">1週間</div>
            </div>
        </div>
        <div class="p-sidebar__brand">
            <h3 class="p-sidebar__brand__title">銘柄</h3>
            <div class="p-sidebar__brand__area">
                <div class="c-action-btn" v-bind:class="[currency1 ? 'c-action-btn--blue':'']" @click="clickBTC">$BTC</div>
                <div class="c-action-btn" v-bind:class="[currency2 ? 'c-action-btn--blue':'']" @click="clickETH">$ETH</div>
                <div class="c-action-btn" v-bind:class="[currency3 ? 'c-action-btn--blue':'']" @click="clickETC">$ETC</div>
                <div class="c-action-btn" v-bind:class="[currency4 ? 'c-action-btn--blue':'']" @click="clickLSK">$LSK</div>
                <div class="c-action-btn" v-bind:class="[currency5 ? 'c-action-btn--blue':'']" @click="clickFCT">$FCT</div>
                <div class="c-action-btn" v-bind:class="[currency6 ? 'c-action-btn--blue':'']" @click="clickXRP">$XRP</div>
                <div class="c-action-btn" v-bind:class="[currency7 ? 'c-action-btn--blue':'']" @click="clickXEM">$XEM</div>
                <div class="c-action-btn" v-bind:class="[currency8 ? 'c-action-btn--blue':'']" @click="clickLTC">$LTC</div>
                <div class="c-action-btn" v-bind:class="[currency9 ? 'c-action-btn--blue':'']" @click="clickBCH">$BCH</div>
                <div class="c-action-btn" v-bind:class="[currency10 ? 'c-action-btn--blue':'']" @click="clickMONA">$MONA</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:[
        'period', //期間(1:1時間, 2:1日, 3:１週間)
        'currency1', // 銘柄検索フラグ ($BTC)
        'currency2', // 銘柄検索フラグ ($ETH)
        'currency3', // 銘柄検索フラグ ($ETC)
        'currency4', // 銘柄検索フラグ ($LSK)
        'currency5', // 銘柄検索フラグ ($FCT)
        'currency6', // 銘柄検索フラグ ($XRP)
        'currency7', // 銘柄検索フラグ ($XEM)
        'currency8', // 銘柄検索フラグ ($LTC)
        'currency9', // 銘柄検索フラグ ($BCH)
        'currency10' // 銘柄検索フラグ ($MONA)
    ],
    methods: {
        // 期間：「1時間」を選択した場合
        hourPeriod:function() {
            // 親コンポーネントに通知
            this.$emit('child-h-period')
        },
         // 期間：「1日」を選択した場合
        datePeriod:function() {
            // 親コンポーネントに通知
            this.$emit('child-d-period')
        },
         // 期間：「1週間」を選択した場合
        weekPeriod:function() {
            // 親コンポーネントに通知
            this.$emit('child-w-period')
        },
        // 銘柄：「$BTC」を選択した場合
        clickBTC:function() {
            // 親コンポーネントに通知
            this.$emit('child-BTC')
        },
        // 銘柄：「$ETH」を選択した場合
        clickETH:function() {
            // 親コンポーネントに通知
            this.$emit('child-ETH')
        },
        // 銘柄：「$ETC」を選択した場合
        clickETC:function() {
            // 親コンポーネントに通知
            this.$emit('child-ETC')
        },
        // 銘柄：「$LSK」を選択した場合
        clickLSK:function() {
            // 親コンポーネントに通知
            this.$emit('child-LSK')
        },
        // 銘柄：「$FCT」を選択した場合
        clickFCT:function() {
            // 親コンポーネントに通知
            this.$emit('child-FCT')
        },
        // 銘柄：「$XRP」を選択した場合
        clickXRP:function() {
            // 親コンポーネントに通知
            this.$emit('child-XRP')
        },
        // 銘柄：「$XEM」を選択した場合
        clickXEM:function() {
            // 親コンポーネントに通知
            this.$emit('child-XEM')
        },
        // 銘柄：「$LTC」を選択した場合
        clickLTC:function() {
            // 親コンポーネントに通知
            this.$emit('child-LTC')
        },
        // 銘柄：「$BCH」を選択した場合
        clickBCH:function() {
            // 親コンポーネントに通知
            this.$emit('child-BCH')
        },
        // 銘柄：「$MONA」を選択した場合
        clickMONA:function() {
            // 親コンポーネントに通知
            this.$emit('child-MONA')
        },
    }
}
</script>
