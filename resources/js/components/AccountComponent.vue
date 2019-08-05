<template>
    <section class="main__base">
        <section class="p-account">
            <div class="p-account__top">
                <div class="p-account__top__area">
                    <h2 class="p-account__top__area__title">Crypto Account</h2>
                    <p v-if="count !== 0" class="p-account__top__area__page">
                        {{firstCount}} - {{lastCount}} / {{count}}
                    </p>
                </div>
                <span class="p-account__top__border"></span>
            </div>
            <AccountMain
            :accounts="accounts"
            :page="page"
            :perPage="perPage"
            @child-follow="follow">
            </AccountMain>
            <div class="p-account__page">
                <a href="#" class="prev" @click="onPrev">&lt; Prev</a>
                <div class="total">ページ {{page}}/{{totalPage}}</div>
                <a href="#" class="next" @click="onNext">Next &gt;</a>
            </div>
        </section>
        <section class="p-sidebar p-sidebar--option">
            <div class="p-sidebar__top">
                <h2 class="p-sidebar__top__title">Option</h2>
                <span class="p-sidebar__top__border"></span>
            </div>
            <AccountOption
            :followLimit="followLimit"
            :unFollowLimit="unFollowLimit"
            :followFlg="followFlg"
            :autoFollowFlg="autoFollowFlg"
            @child-search="searchAccount"
            @child-auto="autoFollow"
            ></AccountOption>
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
            accounts: [], //関連アカウント情報
            page: 1, //現在のページ番号
            perPage: 20, //1ページ毎の表示件数
            totalPage: 0, //総ページ数
            count: 0, //accountsの総数
            firstCount:0, //表示初めの件数
            lastCount:0, //表示終りの件数
            followFlg:0, //フォロー済の有無のフラグ
            users:[], //user情報
            followLimit: 0, //フォロー回数
            unFollowLimit: 0, // フォロー解除回数
            autoFollowFlg:0, //自動フォローフラグ
            twitter_id:0,
            action_flg:1,//フォロー成功の有無フラグ
            act_follow_sign: 0 //フォロー、フォロー解除アクション時のサイン
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
        fetchUser: function() {
            this.$axios.post('/account/user').then((res)=>{
                this.users = res.data
                this.followLimit = this.users.follow_limit
                this.unFollowLimit = this.users.unfollow_limit
                this.autoFollowFlg = this.users.autofollow_flg
            })
        },
        searchAccount: function() {
            this.followFlg = !this.followFlg
        },
        autoFollow: function() {
            this.$axios.post('/account/auto',{
                autoFollow_flg:this.autoFollowFlg
            }).then((res)=>{
                this.users = res.data
                this.autoFollowFlg = this.users.autofollow_flg
            })
        },
        pageCount: function() {
            this.count = this.accounts.length
            this.lastCount = this.page * this.perPage
            this.firstCount = this.lastCount -19
            if(this.lastCount > this.count) {
                this.lastCount = this.count
            }
            this.totalPage = Math.ceil(this.accounts.length / this.perPage)
        },
        onPrev() {
            this.page= Math.max(this.page- 1, 1);
        },
        onNext() {
            this.page= Math.min(this.page+ 1, this.totalPage);
        },
        // 手動フォローメソッド
        follow(id) {
            this.$axios.post('/account/follow',{
                twitter_id:id
            }).then((res)=>{
                this.action_flg = res.data
                this.act_follow_sign = !this.act_follow_sign
            })
        }
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
            if(this.lastCount > this.count) {
                this.lastCount = this.count
            }
        },
        followFlg: function() {
            this.fetchAccount()
        },
        act_follow_sign:function() {
            this.fetchAccount()
        }
    },
    created() {
        this.fetchAccount()
        this.fetchUser()
    }
}
</script>
