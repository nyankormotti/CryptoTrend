<template>
    <div class="p-sidebar__area">
        <div class="p-sidebar__period">
            <h3 class="p-sidebar__period__title">期間</h3>
            <div class="p-sidebar__period__area">
                <div v-if="period == 1" class="c-action-btn c-action-btn--blue">1時間</div>
                <div v-else class="c-action-btn" @click="searchHourPeriod">1時間</div>
                <div v-if="period == 2" class="c-action-btn c-action-btn--blue">1日間</div>
                <div v-else class="c-action-btn" @click="searchDatePeriod">1日間</div>
                <div v-if="period == 3" class="c-action-btn c-action-btn--blue">1週間</div>
                <div v-else class="c-action-btn" @click="searchWeekPeriod">1週間</div>
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
    data: function() {
        return {
            search: [],
            period: '',
            currency1: false,
            currency2: false,
            currency3: false,
            currency4: false,
            currency5: false,
            currency6: false,
            currency7: false,
            currency8: false,
            currency9: false,
            currency10: false
        }
    },
    methods: {
        searchHourPeriod:function() {
            this.period = 1;
        },
        searchDatePeriod:function() {
            this.period = 2;
        },
        searchWeekPeriod:function() {
            this.period = 3;
        },
        clickBTC:function() {
            this.currency1 = !this.currency1;
        },
        clickETH:function() {
            this.currency2 = !this.currency2;
        },
        clickETC:function() {
            this.currency3 = !this.currency3;
        },
        clickLSK:function() {
            this.currency4 = !this.currency4;
        },
        clickFCT:function() {
            this.currency5 = !this.currency5;
        },
        clickXRP:function() {
            this.currency6 = !this.currency6;
        },
        clickXEM:function() {
            this.currency7 = !this.currency7;
        },
        clickLTC:function() {
            this.currency8 = !this.currency8;
        },
        clickBCH:function() {
            this.currency9 = !this.currency9;
        },
        clickMONA:function() {
            this.currency10 = !this.currency10;
        },
    },
    created() {
        this.searchHourPeriod()
    },
}
</script>
