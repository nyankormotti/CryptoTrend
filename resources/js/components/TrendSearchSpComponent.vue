<template>
    <section id="trend_main_template" class="p-sidebar p-sidebar--search p-sidebar--search--sp">
        <div class="p-sidebar__top">
            <h2 class="p-sidebar__top__title">Search</h2>
            <span class="p-sidebar__top__border"></span>
        </div>
        <div class="p-sidebar__area">
            <div class="p-sidebar__period">
                <h3 class="p-sidebar__period__title">期間</h3>
                <div class="p-sidebar__period__area">
                    <div v-if="period == 1" class="c-action-btn c-action-btn--trend c-action-btn--blue">1時間</div>
                    <div v-else class="c-action-btn c-action-btn--trend" @click="hourPeriod">1時間</div>
                    <div v-if="period == 2" class="c-action-btn c-action-btn--trend c-action-btn--blue">1日間</div>
                    <div v-else class="c-action-btn c-action-btn--trend" @click="datePeriod">1日間</div>
                    <div v-if="period == 3" class="c-action-btn c-action-btn--trend c-action-btn--blue">1週間</div>
                    <div v-else class="c-action-btn c-action-btn--trend" @click="weekPeriod">1週間</div>
                </div>
            </div>
            <div class="p-sidebar__accordion">
                <div v-if="!openFlg" class="p-sidebar__accordion__area p-sidebar__accordion__area--brand">
                    <img class="p-sidebar__accordion__area__img" :src="'/assets/open_icon.png'">
                    <p class="p-sidebar__accordion__area__state p-sidebar__accordion__area__state--open" @click="show">銘柄を絞る</p>
                </div>
            </div>
            <!-- アコーディオン表示領域 -->
            <div class="p-sidebar__brand p-sidebar__sp p-sidebar__sp--brand">
                <h3 class="p-sidebar__brand__title">銘柄</h3>
                <div class="p-sidebar__brand__area">
                    <label for="BTC"  class="p-sidebar__brand__check" >
                        <input type="checkbox" id="BTC" @click="clickBTC" v-model="currency1">$BTC
                    </label>
                    <label for="ETH"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="ETH" @click="clickETH" v-model="currency2">$ETH
                    </label>
                    <label for="ETC"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="ETC" @click="clickETC" v-model="currency3">$ETC
                    </label>
                    <label for="LSK"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="LSK" @click="clickLSK" v-model="currency4">$LSK
                    </label>
                    <label for="FCT"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="FCT" @click="clickFCT" v-model="currency5">$FCT
                    </label>
                    <label for="XRP"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="XRP" @click="clickXRP" v-model="currency6">$XRP
                    </label>
                    <label for="XEM"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="XEM" @click="clickXEM" v-model="currency7">$XEM
                    </label>
                    <label for="LTC"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="LTC" @click="clickLTC" v-model="currency8">$LTC
                    </label>
                    <label for="BCH"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="BCH" @click="clickBCH" v-model="currency9">$BCH
                    </label>
                    <label for="MONA"  class="p-sidebar__brand__check">
                        <input type="checkbox" id="MONA" @click="clickMONA" v-model="currency10">$MONA
                    </label>
                </div>
            </div>
            <div class="p-sidebar__accordion">
                <div v-if="openFlg" class="p-sidebar__accordion__area p-sidebar__accordion__area--close">
                    <img class="p-sidebar__accordion__area__img" :src="'/assets/close_icon.png'">
                    <p class="p-sidebar__accordion__area__state p-sidebar__accordion__area__state--open" @click="show">閉じる</p>
                </div>
            </div>
             <!-- アコーディオン表示領域 終-->
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            openFlg:0
        }
    },
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
         // オプションを開くor閉じる
        show: function() {
            let option = document.querySelector('.p-sidebar__sp--brand')
            option.classList.toggle('p-sidebar__sp')
            this.openFlg = !this.openFlg
        },
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
