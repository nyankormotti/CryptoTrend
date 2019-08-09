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
            :followFlg="followFlg"
            @child-follow="follow"
            @child-unfollow="unfollow">
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
import { setInterval } from 'timers';

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
            result_follow_flg:1,//手動フォローの結果フラグ
            act_follow_sign: 0,//フォローリクエスト時のサイン
            result_unfollow_flg:1,//手動フォロー解除の結果フラグ
            act_unfollow_sign: 0, //フォロー解除リクエスト時のサイン
            intervalId: undefined // setInterval用
        }
    },
    methods: {
        fetchAccount: function() {
            this.$axios.post('/account/get',{
                follow_flg:this.followFlg
            }).then((res)=>{
                this.accounts = res.data
            }).catch(err => {
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
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
            }).catch(err => {
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
        },
        pageCount: function() {
            this.count = this.accounts.length
            this.lastCount = this.page * this.perPage
            if(this.count % 20 == 0 || this.lastCount <= 20) {
                this.firstCount = 1
            } else if(this.lastCount <= this.count){
                this.firstCount = this.lastCount -19
            } else {
                this.firstCount = (this.page - 1) * this.perPage + 1
            }

            if(this.lastCount > this.count) {
                this.lastCount = this.count
                this.firstCount = (this.page - 1) * this.perPage + 1
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
                this.result_follow_flg = res.data
                this.act_follow_sign = !this.act_follow_sign
            }).catch(err => {
                this.act_follow_sign = !this.act_follow_sign
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
        },
        // 手動フォロー解除メソッド
        unfollow(id) {
            this.$axios.post('/account/unfollow',{
                twitter_id:id
            }).then((res)=>{
                this.result_unfollow_flg = res.data
                this.act_unfollow_sign = !this.act_unfollow_sign
            }).catch(err => {
                this.act_follow_sign = !this.act_follow_sign
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
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
            if(this.totalPage < this.page){
                this.page = this.totalPage
            }
            this.lastCount = this.page * this.perPage
            if(this.count % 20 == 0 || this.lastCount <= 20) {
                this.firstCount = 1
            } else if(this.lastCount <= this.count){
                this.firstCount = this.lastCount -19
            } else {
                this.firstCount = (this.page - 1) * this.perPage + 1
            }
            if(this.lastCount > this.count) {
                this.lastCount = this.count
                this.firstCount = (this.page - 1) * this.perPage + 1
            } 
        },
        count: function() {
            if(this.totalPage < this.page){
                this.page = this.totalPage
            } else if(this.count !== 0 && this.page == 0) {
                this.page = 1
                this.lastCount = this.page * this.perPage
            }
            this.lastCount = this.page * this.perPage
            if(this.count % 20 == 0 || this.lastCount <= 20) {
                this.firstCount = 1
            } else {
                this.firstCount = (this.page - 1) * this.perPage + 1
            }
            if(this.lastCount > this.count) {
                this.lastCount = this.count
            }
        },
        followFlg: function() {
            this.fetchAccount()
        },
        // 手動フォローリクエスト時のサイン
        // 手動フォロー実行後に処理を実行する。
        act_follow_sign: function() {
            // API連携が失敗した場合、アラートを発行
            if(this.result_follow_flg == 0) {
                alert('そのアカウントはフォローできません。')
            } else if(this.result_follow_flg == 2) {
                alert('15分間のリクエスト回数を超えているため、フォローできません。')
            } else if(this.result_follow_flg == 3) {
                alert('1日のフォロー上限回数を超えているため、処理できません。')
            } else if(this.result_follow_flg == 4) {
                alert('そのアカウントはフォロー済みです。')
            }
            this.result_follow_flg = 1
            this.fetchAccount()
            this.fetchUser();
        },
        // 手動フォロー解除リクエスト時のサイン
        // 手動フォロー解除実行後に処理を実行する。
        act_unfollow_sign: function() {
            // API連携が失敗した場合、アラートを発行
            if(this.result_unfollow_flg == 0) {
                alert('そのアカウントはフォロー解除できません。')
            } else if(this.result_unfollow_flg == 2) {
                alert('15分間のリクエスト回数を超えているため、フォロー解除できません。')
            } else if(this.result_unfollow_flg == 3) {
                alert('1日のフォロー解除上限回数を超えているため、処理できません。')
            } else if(this.result_unfollow_flg == 4) {
                alert('そのアカウントはフォロー解除済です。')
            }
            this.result_unfollow_flg = 1
            this.fetchAccount()
            this.fetchUser();
        }
    },
    created() {
        this.fetchAccount()
        this.fetchUser()
    },
    mounted () {
        let that = this
        // 毎分、最新のアカウント情報とユーザー情報を描画(DBからデータを取得)
        // 自動フォローをバッチで行なっているため、リアルタイムでの描画が必要となる(自動フォロー処理は30分毎に1アカウントをフォローしている)
        this.intervalId = setInterval(function() {
            that.fetchAccount()
            that.fetchUser()
            console.log('setInterval')
        },60000);
    },
    beforeDestroy() {
        // Vueインスタンスが破壊される直前に、setInteval処理を中断する。
        console.log('clearInterval')
        clearInterval(this.intervalId)
    }
}
</script>
