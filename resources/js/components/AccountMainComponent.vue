<template>
    <div class="p-account__main">
        <div v-for="(account, i) in filterAccounts" :key="i" class="p-user">
            <div class="p-user__top">
                <h2 class="p-user__top__name">{{account.account_name}}</h2>
                <div class="p-user__top__btn c-action-btn" :name="account.twiiter_id">フォロー</div>
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
                    <h3 class="p-user__text__tweet__title">最新ツイート</h3>
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
    ],
    data: function() {
        return {
        }
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
