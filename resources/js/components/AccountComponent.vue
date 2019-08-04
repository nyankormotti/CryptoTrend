<template>
    <section class="main__base">
        <section class="p-account">
            <div class="p-account__top">
                <div class="p-account__top__area">
                    <h2 class="p-account__top__area__title">Crypto Account</h2>
                    <p class="p-account__top__area__page">{{firstCount}} - {{lastCount}} / {{count}}</p>
                </div>
                <span class="p-account__top__border"></span>
            </div>
            <AccountMain
            :accounts="accounts"
            :page="page"
            :perPage="perPage"
            :totalPage="totalPage">
            </AccountMain>
            <div class="p-account__page">
                <a href="#" class="prev" @click="onPrev">&lt; 前へ</a>
                <div class="total">ページ {{page}}/{{totalPage}}</div>
                <a href="#" class="next" @click="onNext">次へ &gt;</a>
            </div>
        </section>
        <section class="p-sidebar p-sidebar--option">
            <div class="p-sidebar__top">
                <h2 class="p-sidebar__top__title">Option</h2>
                <span class="p-sidebar__top__border"></span>
            </div>
            <div class="p-sidebar__limit">
                <h3 class="p-sidebar__limit__title">上限回数</h3>
                <div class="p-sidebar__limit__area">
                    <div class="p-sidebar__limit__area--f_action">
                        <h3 class="p-sidebar__limit__area--f_action--title">フォロー</h3>
                        <p class="p-sidebar__limit__area--f_action--count"><span class="p-sidebar__limit__area--f_action--count--now">0</span> / 25</p>
                    </div>
                    <div class="p-sidebar__limit__area--f_action">
                        <h3 class="p-sidebar__limit__area--f_action--title">フォロー解除</h3>
                        <p class="p-sidebar__limit__area--f_action--count"><span class="p-sidebar__limit__area--f_action--count--now">0</span> / 100</p>
                    </div>
                </div>
            </div>
            <div class="p-sidebar__follow">
                <h3 class="p-sidebar__follow__title">表示形式</h3>
                <div class="p-sidebar__follow__area">
                    <div class="c-action-btn c-action-btn--follow">未フォロー</div>
                    <div class="c-action-btn c-action-btn--follow">フォロー済</div>
                </div>
            </div>

            <div class="p-sidebar__follow">
                <h3 class="p-sidebar__follow__title">自動フォロー</h3>
                <div class="p-sidebar__follow__area">
                    <div class="c-action-btn c-action-btn--follow">ON</div>
                    <div class="c-action-btn c-action-btn--follow">OFF</div>
                </div>
            </div>
        </section>
    </section>
</template>

<script>

import AccountMain from './AccountMainComponent.vue'
import AccountOption from './AccountOptionComponent.vue'

export default {
    components: {
        AccountMain,
        AccountOption
    },
    data: function() {
        return {
            accounts: [],
            page: 1, //現在のページ番号
            perPage: 20, //1ページ毎の表示件数
            totalPage: 0, //総ページ数
            count: 0, //accountsの総数
            firstCount:0, //表示初めの件数
            lastCount:0, //表示終りの件数
            followFlg:0, //フォロー済の有無のflg
        }
    },
    methods: {
        fetchAccount: function() {
            this.$axios.post('/account/get',{
                follow_flg:this.followFlg
            }).then((res)=>{
                this.accounts = res.data
            })
        },
        pageCount: function() {
            this.count = this.accounts.length
            this.lastCount = this.page * this.perPage
            this.firstCount = this.lastCount -19
            this.totalPage = Math.ceil(this.accounts.length / this.perPage)
        },
        onPrev() {
            this.page= Math.max(this.page- 1, 1);
        },
        onNext() {
            this.page= Math.min(this.page+ 1, this.totalPage);
        },
    },
    computed: {
        prevPage() {
            return Math.max(this.page - 1, 1);
        },
        nextPage() {
            return Math.min(this.page + 1, this.totalPage);
        }
    },
    watch: {
        accounts: function() {
            this.pageCount()
        },
        page: function() {
            this.lastCount = this.page * this.perPage
            this.firstCount = this.lastCount -19
        }
    },
    created() {
        this.fetchAccount()
    },
    beforeUpdate() {
        this.fetchAccount()
    }
}
</script>
