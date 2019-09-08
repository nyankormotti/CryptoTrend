<template>
    <section class="p-sidebar p-sidebar--option p-sidebar--option--sp">
        <div class="p-sidebar__top">
            <h2 class="p-sidebar__top__title">Status</h2>
            <span class="p-sidebar__top__border"></span>
        </div>
        <div class="p-sidebar__area p-sidebar__area--account">
            <div class="p-sidebar__twitter">
                <div class="p-sidebar__twitter__img">
                    <img :src="twitterUserAccounts.profile_image_url" alt="" class="p-sidebar__twitter__img__area">
                </div>
                <div class="p-sidebar__twitter__describe">
                    <p class="p-sidebar__twitter__name">{{twitterUserAccounts.name}}</p>
                    <p class="p-sidebar__twitter__screen">{{twitterUserAccounts.screen_name}}</p>
                    <div class="p-sidebar__twitter__status">
                        <div class="p-sidebar__twitter__status__folow">
                            <p class="p-sidebar__twitter__status__folow__describe">フォロー</p>
                            <p class="p-sidebar__twitter__status__folow__describe--value">{{twitterUserAccounts.friends_count}}</p>
                        </div>
                        <div class="p-sidebar__twitter__status__folower">
                            <p class="p-sidebar__twitter__status__folower__describe">フォロワー</p>
                            <p class="p-sidebar__twitter__status__folower__describe--value">{{twitterUserAccounts.followers_count}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-sidebar__accordion">
                <div v-if="!openFlg" class="p-sidebar__accordion__area">
                    <img class="p-sidebar__accordion__area__img" :src="'/assets/open_icon.png'">
                    <p class="p-sidebar__accordion__area__state p-sidebar__accordion__area__state--open" @click="show">設定</p>
                </div>
            </div>
            <!-- アコーディオン表示領域 -->
            <div class="p-sidebar__sp p-sidebar__sp--dom">
                <div class="p-sidebar__follow">
                    <h3 class="p-sidebar__follow__title p-sidebar__follow__title--display">表示形式</h3>
                    <div class="p-sidebar__follow__area">
                        <div v-if="!followFlg" class="c-action-btn c-action-btn--follow c-action-btn--follow--optionBlue">
                            未フォロー
                        </div>
                        <div v-else class="c-action-btn c-action-btn--follow" @click="search">未フォロー</div>
                        <div v-if="followFlg" class="c-action-btn c-action-btn--follow c-action-btn--follow--optionBlue">
                            フォロー済
                        </div>
                        <div v-else class="c-action-btn c-action-btn--follow" @click="search">フォロー済</div>
                    </div>
                </div>

                <div class="p-sidebar__follow">
                    <h3 class="p-sidebar__follow__title">自動フォロー</h3>
                    <div class="p-sidebar__follow__area">
                        <div v-if="autoFollowFlg" class="c-action-btn c-action-btn--follow c-action-btn--follow--optionGreen">ON</div>
                        <div v-else  class="c-action-btn c-action-btn--follow" @click="autoFollow">ON</div>
                        <div v-if="!autoFollowFlg" class="c-action-btn c-action-btn--follow c-action-btn--follow--optionGreen">OFF</div>
                        <div v-else class="c-action-btn c-action-btn--follow" @click="autoFollow">OFF</div>
                    </div>
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
    props: [
        "twitterUserAccounts",//ユーザーのTwitterアカウント情報
        "followLimit", //フォロー回数
        "unFollowLimit", // フォロー解除回数
        "autoFollowFlg", //自動フォローフラグ (0:OFF, 1:ON)
        "followFlg", //フォローの有無フラグ (0:未フォロー, 1:フォロー済)
    ],
    methods: {
        // オプションを開くor閉じる
        show: function() {
            let option = document.querySelector('.p-sidebar__sp--dom')
            option.classList.toggle('p-sidebar__sp')
            this.openFlg = !this.openFlg
        },
        // 「未フォロー」または「フォロー済」ボタンをクリック
        search: function() {
            // 親コンポーネントに通知
            this.$emit('child-search')
        },
        // 自動フォローの「ON」または「OFF」ボタンをクリック
        autoFollow: function() {
            // 親コンポーネントに通知
            this.$emit('child-auto')
        }
    }
}
</script>
