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
        'period',
        'currency1',
        'currency2',
        'currency3',
        'currency4',
        'currency5',
        'currency6',
        'currency7',
        'currency8',
        'currency9',
        'currency10'
    ],
    methods: {
        hourPeriod:function() {
            this.$emit('child-h-period')
        },
        datePeriod:function() {
            this.$emit('child-d-period')
        },
        weekPeriod:function() {
            this.$emit('child-w-period')
        },
        clickBTC:function() {
            this.$emit('child-BTC')
        },
        clickETH:function() {
            this.$emit('child-ETH')
        },
        clickETC:function() {
            this.$emit('child-ETC')
        },
        clickLSK:function() {
            this.$emit('child-LSK')
        },
        clickFCT:function() {
            this.$emit('child-FCT')
        },
        clickXRP:function() {
            this.$emit('child-XRP')
        },
        clickXEM:function() {
            this.$emit('child-XEM')
        },
        clickLTC:function() {
            this.$emit('child-LTC')
        },
        clickBCH:function() {
            this.$emit('child-BCH')
        },
        clickMONA:function() {
            this.$emit('child-MONA')
        },

    }
}
</script>
