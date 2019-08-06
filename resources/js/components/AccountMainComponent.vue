<template>
    <div class="p-account__main">
        <div v-for="(account, i) in filterAccounts" :key="i" class="p-user">
            <div class="p-user__top">
                <h2 class="p-user__top__name">{{account.account_name}}</h2>
                <div v-if="!followFlg" class="p-user__top__btn c-action-btn" v-on:click="follow(account.twitter_id)">フォロー</div>
                <div v-else class="p-user__top__btn--actUnFollow c-action-btn" v-on:click="unfollow(account.twitter_id)">フォロー解除</div>
            </div>
            <div class="p-user__status">
                <p class="p-user__status__screen">@{{account.screen_name}}</p>
                <p class="p-user__status__fcount">{{account.follow}} フォロー | {{account.follower}} フォロワー</p>
            </div>
            <div class="p-user__text">
                <div class="p-user__text__profile">
                    <p class="p-user__text__profile__describe" key="text">{{account.profile}}</p>
                </div>
                <span class="p-user__text__border"></span>
                <div class="p-user__text__tweet">
                    <h3 v-if="!followFlg" class="p-user__text__tweet__title">最新ツイート</h3>
                    <h3 v-else class="p-user__text__tweet__title--actUnFollow">最新ツイート</h3>
                    <p class="p-user__text__tweet__describe" key="text">{{account.recent_tweet}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'accounts',
        'page',
        'perPage',
        'followFlg'
    ],
    data: function() {
        return {
        }
    },
    methods: {
        follow: function(twitter_id) {
            this.$emit('child-follow',twitter_id)
        },
        unfollow: function(twitter_id) {
            this.$emit('child-unfollow',twitter_id)
        },
    },
    computed: {
        filterAccounts() {
            return this.accounts.filter(
            (account, i) =>
                i >= (this.page - 1) * this.perPage &&
                i < this.page * this.perPage
            );
        }
    }
}
</script>
