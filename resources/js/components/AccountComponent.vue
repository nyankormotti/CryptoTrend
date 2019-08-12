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
            accounts: [], //仮想通貨関連アカウント情報
            users:[], //ユーザー情報

            // ページネーション関連
            count: 0, //仮想通貨関連アカウント情報の総数
            page: 1, //現在のページ番号
            perPage: 20, //1ページ毎の表示件数
            totalPage: 0, //総ページ数
            firstCount:0, //表示初めの件数
            lastCount:0, //表示終りの件数

            // option領域のデータ
            followFlg:0, //フォローの有無フラグ(表示形式変更フラグ：アカウント情報の表示形式を変更するフラグ)
                         // (0:未フォロー, 1:フォロー済)
            followLimit: 0, //フォロー回数
            unFollowLimit: 0, // フォロー解除回数
            autoFollowFlg:0, //自動フォローフラグ

            // 手動フォロー時のフラグ、サイン
            resultFollowFlg:1,//手動フォローの結果フラグ (0：API連携失敗, 1：正常終了, 2：リクエスト回数超過, 3：フォロー回数超過, 4：フォロー済アカウントをフォローしようとした場合)
            actFollowSign: 0,//フォローリクエスト時のサイン (0:リクエスト完了)

            // 手動フォロー解除時のフラグ、サイン
            resultUnfollowFlg:1,//手動フォロー解除の結果フラグ(0：API連携失敗, 1：正常終了, 2：リクエスト回数超過, 3：フォロー解除回数超過, 4：未フォローのアカウントをフォロー解除しようとした場合)
            actUnfollowSign: 0, //フォロー解除リクエスト時のサイン

            // setInterval用変数
            intervalId: undefined
        }
    },
    methods: {
        // 仮想通貨関連アカウント情報取得処理
        fetchAccount: function() {
            this.$axios.post('/account/get',{
                // フォローの有無フラグにてフォロー済み、または未フォローのアカウント情報を取得
                follow_flg:this.followFlg
            }).then((res)=>{
                this.accounts = res.data
            }).catch(err => {
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
        },
        // ユーザー情報取得処理
        fetchUser: function() {
            this.$axios.post('/account/user').then((res)=>{
                // ユーザー情報
                this.users = res.data
                // ユーザー情報のフォロー回数
                this.followLimit = this.users.follow_limit
                // ユーザー情報のフォロー解除回数
                this.unFollowLimit = this.users.unfollow_limit
                // ユーザー情報の自動フォロー有無フラグ
                this.autoFollowFlg = this.users.autofollow_flg
            })
        },
        // フォロー有無フラグ(アカウント情報の表示形式)変更処理
        searchAccount: function() {
            // アカウント情報の表示形式変更(未フォロー：0 or フォロー済：1)
            // ウォッチャにて実施
            this.followFlg = !this.followFlg
        },
        // 自動フォローフラグ更新処理
        autoFollow: function() {
            // 自動フォロー有無の値が変更された際に実施
            // ユーザー情報の自動フォローフラグを更新する(ON or OFF)
            this.$axios.post('/account/auto',{
                autoFollow_flg:this.autoFollowFlg
            }).then((res)=>{
                // ユーザー情報
                this.users = res.data
                // ユーザー情報の自動フォロー有無フラグ
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
                this.resultFollowFlg = res.data
                this.actFollowSign = !this.actFollowSign
            }).catch(err => {
                this.actFollowSign = !this.actFollowSign
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
        },
        // 手動フォロー解除メソッド
        unfollow(id) {
            this.$axios.post('/account/unfollow',{
                twitter_id:id
            }).then((res)=>{
                this.resultUnfollowFlg = res.data
                this.actUnfollowSign = !this.actUnfollowSign
            }).catch(err => {
                this.actFollowSign = !this.actFollowSign
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
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
            // 表示形式変更フラグが変わった際に、アカウント情報を取得する
            // followFlgにてフォロー済み、または未フォローのアカウント情報を取得する。
            this.fetchAccount()
        },
        // 手動フォローリクエスト時のサイン
        // 手動フォロー実行後に処理を実行する。
        actFollowSign: function() {
            if(this.resultFollowFlg == 0) {
                // API連携が失敗した場合、アラートを発行
                alert('そのアカウントはフォローできません。')
            } else if(this.resultFollowFlg == 2) {
                // リクエスト制限が超えている場合、アラートを発行
                alert('15分間のリクエスト回数を超えているため、フォローできません。')
            } else if(this.resultFollowFlg == 3) {
                // 1日のフォロー制限を超えている場合、アラートを発行
                alert('1日のフォロー上限回数を超えているため、処理できません。')
            } else if(this.resultFollowFlg == 4) {
                // フォロー済みのアカウントの場合、アラートを発行
                alert('そのアカウントはフォロー済みです。')
            }
            this.resultFollowFlg = 1
            // 仮想通貨関連アカウント情報取得
            this.fetchAccount()
            // ユーザー情報取得
            this.fetchUser()
        },
        // 手動フォロー解除リクエスト時のサイン
        // 手動フォロー解除実行後に処理を実行する。
        actUnfollowSign: function() {
            // API連携が失敗した場合、アラートを発行
            if(this.resultUnfollowFlg == 0) {
                alert('そのアカウントはフォロー解除できません。')
            } else if(this.resultUnfollowFlg == 2) {
                alert('15分間のリクエスト回数を超えているため、フォロー解除できません。')
            } else if(this.resultUnfollowFlg == 3) {
                alert('1日のフォロー解除上限回数を超えているため、処理できません。')
            } else if(this.resultUnfollowFlg == 4) {
                alert('そのアカウントはフォロー解除済です。')
            }
            this.resultUnfollowFlg = 1
            // 仮想通貨関連アカウント情報取得
            this.fetchAccount()
            // ユーザー情報取得
            this.fetchUser()
        }
    },
    created() {
        // 初期表示時に、アカウント情報、ユーザー情報を取得
        this.fetchAccount()
        this.fetchUser()
    },
    mounted () {
        let that = this
        // 毎分、最新のアカウント情報とユーザー情報を描画(DBからデータを取得)
        // 自動フォローをバッチで行なっているため、リアルタイムでの描画が必要となる(自動フォロー処理は30分毎に1アカウントをフォローしている)
        this.intervalId = setInterval(function() {
            // 仮想通貨関連アカウント情報取得
            that.fetchAccount()
            // ユーザー情報取得
            that.fetchUser()
        },60000);
    },
    beforeDestroy() {
        // Vueインスタンスが破壊される直前に、setInteval処理を中断する。
        clearInterval(this.intervalId)
    }
}
</script>
