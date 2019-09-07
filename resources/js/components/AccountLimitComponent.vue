<template>
    <section class="p-sidebar p-sidebar--limit">
        <div class="p-sidebar__top">
            <h2 class="p-sidebar__top__title">Follow Limit</h2>
            <span class="p-sidebar__top__border"></span>
        </div>
        <div class="p-sidebar__limit">
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
                        </span> / 25
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: [
        "followLimit", //フォロー回数
        "unFollowLimit", // フォロー解除回数
    ]
}
</script>>