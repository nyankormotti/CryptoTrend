<template>
    <section class="main__base">
        <!-- 関連アカウント表示領域 -->
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
                <a href="#" class="p-account__page__prev" @click="onPrev">&lt; 前ページ</a>
                <div v-if="totalPage != 0" class="p-account__page__total">{{page}}/{{totalPage}}</div>
                <div v-else class="p-account__page__total">0/{{totalPage}}</div>
                <a href="#" class="p-account__page__next" @click="onNext">次ページ &gt;</a>
            </div>
        </section>

        <!-- オプション領域 -->
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

import AccountMain from './AccountMainComponent.vue' //関連アカウント表示領域
import AccountOption from './AccountOptionComponent.vue' //オプション領域
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
            autoFollowFlg:0, //自動フォローフラグ (0:OFF, 1:ON)

            // 手動フォロー時のフラグ、サイン
            resultFollowFlg:1,//手動フォローの結果フラグ (0：API連携失敗, 1：正常終了, 2：リクエスト回数超過, 3：フォロー回数超過, 4：フォロー済アカウントをフォローしようとした場合)
            actFollowSign: 0,//フォローリクエスト時のサイン (値が変化した際に手動フォロー後の処理を実行するサイン)

            // 手動フォロー解除時のフラグ、サイン
            resultUnfollowFlg:1,//手動フォロー解除の結果フラグ(0：API連携失敗, 1：正常終了, 2：リクエスト回数超過, 3：フォロー解除回数超過, 4：未フォローのアカウントをフォロー解除しようとした場合)
            actUnfollowSign: 0, //フォロー解除リクエスト時のサイン (値が変化した際に手動フォロー解除後の処理を実行するサイン)

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
                //仮想通貨関連アカウント情報
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
        // フォロー有無フラグ(アカウント情報の表示形式)変更処理(子コンポーネントの未フォロー」または「フォロー済」ボタンをクリックした際に実行)
        searchAccount: function() {
            // アカウント情報の表示形式変更(未フォロー：0 or フォロー済：1)
            // ウォッチャにて実施
            this.followFlg = !this.followFlg
        },
        // 自動フォローフラグ更新処理(子コンポーネントの自動フォローの「ON」または「OFF」ボタンをクリックした際に実行)
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
        // ページングの編集処理
        pageCount: function() {
            // 総ページ数の算出
            this.totalPage = Math.ceil(this.accounts.length / this.perPage)
            // 総ページ数がデフォルト値ではなく、かつ現在ページよりも小さい時
            if(this.totalPage != 0 && this.page > this.totalPage) {
                // 現在ページを総ページと同じ値にする
                this.page = this.totalPage
            } 
            // アカウント情報の総数
            this.count = this.accounts.length
            // 1ページの表示終りの件数 = 現在のページ数 * 1ページの表示件数(20件)
            this.lastCount = this.page * this.perPage

            // 表示終りの件数がアカウント情報の総数より大きい場合
            if(this.lastCount > this.count) {
                // 表示終りの件数 = アカウント情報の総数
                this.lastCount = this.count
            }
            // 1ページの表示初めの件数 = (現在のページ数 - 1) * 1ページの表示件数(20件) + 1
            // 例：現在のページ数 = 1の場合 → 表示初めの件数:1
            // 例：現在のページ数 = 2の場合 → 表示初めの件数:21
            this.firstCount = (this.page - 1) * this.perPage + 1
        },
        // ページングの「Prev」ボタンクリック時、前のページに戻る
        onPrev() {
            this.page= Math.max(this.page- 1, 1);
        },
        // ページングの「Next」ボタンクリック時、次のページに移動
        onNext() {
            this.page= Math.min(this.page+ 1, this.totalPage);
        },
        // 手動フォローメソッド(「フォロー」ボタンをクリック時に実行)
        follow(id) {
            this.$axios.post('/account/follow',{
                // 子コンポーネントから受け取ったTwitter_idをサーバーサイドへ渡す
                twitter_id:id
            }).then((res)=>{
                // 手動フォローの結果フラグ 
                this.resultFollowFlg = res.data
                // フォローリクエスト時のサインの値を反転(手動フォロー後の処理をウォッチャで実施するため)
                this.actFollowSign = !this.actFollowSign
            }).catch(err => {
                // フォローリクエスト時のサインの値を反転(手動フォロー後の処理をウォッチャで実施するため)
                this.actFollowSign = !this.actFollowSign
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
        },
        // 手動フォロー解除メソッド(「フォロー解除」ボタンをクリック時に実行)
        unfollow(id) {
            this.$axios.post('/account/unfollow',{
                // 子コンポーネントから受け取ったTwitter_idをサーバーサイドへ渡す
                twitter_id:id
            }).then((res)=>{
                // 手動フォロー解除の結果フラグ 
                this.resultUnfollowFlg = res.data
                // フォロー解除リクエスト時のサインの値を反転(手動フォロー解除後の処理をウォッチャで実施するため)
                this.actUnfollowSign = !this.actUnfollowSign
            }).catch(err => {
                // フォロー解除リクエスト時のサインの値を反転(手動フォロー解除後の処理をウォッチャで実施するため)
                this.actFollowSign = !this.actFollowSign
                alert('例外が発生しました。しばらく経ってからお試しください。')
            });
        }
    },
    watch: {
        // アカウント情報が変更された際、ページングの編集処理を実行
        accounts: function() {
            this.pageCount()
        },
        // 現在のページが変更された際、ページングの編集処理を実行
        page: function() {
            this.pageCount()
        },
        // アカウント総数が変わった際、ページングの編集処理を実行
        count: function() {
            this.pageCount()
        },
        // 表示形式変更フラグが変わった際に、アカウント情報を取得する
        followFlg: function() {
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
            // 手動フォローの結果フラグ をデフォルト値に戻す
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
            // 手動フォロー解除の結果フラグ をデフォルト値に戻す
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
