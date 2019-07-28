$(function(){
    // アコーディオンメニュー
    $('.toggle_switch').on('click', function () {
        console.log('トグル');
        $(this).toggleClass('open');
        $(this).next('.toggle_contents').slideToggle();
    });
});