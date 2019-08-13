<template>
    <div class="p-sidebar__area">
        <div class="p-sidebar__limit">
            <h3 class="p-sidebar__limit__title">上限回数</h3>
            <div class="p-sidebar__limit__area">
                <div class="p-sidebar__limit__area--f_action">
                    <h3 class="p-sidebar__limit__area--f_action--title">フォロー</h3>
                    <p class="p-sidebar__limit__area--f_action--count">
                        <span class="p-sidebar__limit__area--f_action--count--now">
                            {{followLimit}}
                        </span> / 25
                    </p>
                </div>
                <div class="p-sidebar__limit__area--f_action">
                    <h3 class="p-sidebar__limit__area--f_action--title">フォロー解除</h3>
                    <p class="p-sidebar__limit__area--f_action--count">
                        <span class="p-sidebar__limit__area--f_action--count--now">
                            {{unFollowLimit}}
                        </span> / 100
                    </p>
                </div>
            </div>
        </div>
        <div class="p-sidebar__follow">
            <h3 class="p-sidebar__follow__title">表示形式</h3>
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
</template>

<script>
export default {
    props: [
        "followLimit", //フォロー回数
        "unFollowLimit", // フォロー解除回数
        "autoFollowFlg", //自動フォローフラグ (0:OFF, 1:ON)
        "followFlg", //フォローの有無フラグ (0:未フォロー, 1:フォロー済)
    ],
    methods: {
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
