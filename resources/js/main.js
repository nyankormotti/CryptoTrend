$(function(){

    // ===========================================================
    // バリデーションエラー時に、エラー個所まで画面をスクロールさせる(トップページのお問い合わせ機能のみに適用)
    // ===========================================================
    let targetoffset = null;
    let err = $(".js-u-err__msg__main");
    if (err.length !== 0) {
        targetoffset = err.offset();
        let hight = $("html,body");
        hight.animate({ scrollTop: targetoffset.top - 80 }, { queue: false });
    }

    // ===========================================================
    // 続きを読むボタン(関連ニュース画面)
    // ===========================================================
    // 分割したい個数を入力
    let division = 5;
    // 要素の数を数える
    let divlength = $('.p-news__content__article').length;
    //分割数で割る
    dlsizePerResult = divlength / division;
    //分割数 刻みで後ろにmorelinkを追加する
    for (i = 1; i <= dlsizePerResult; i++) {
        $('.p-news__content__article').eq(division * i - 1)
            .after('<span class="morelink link' + i + '">もっと見る</span>');
    }
    //最初のli（分割数）と、morelinkを残して他を非表示
    $('.p-news__content__article,.morelink').hide();
    for (j = 0; j < division; j++) {
        $('.p-news__content__article').eq(j).show();
    }
    $('.morelink.link1').show();

    //morelinkにクリック時の動作
    $('.morelink').click(function () {
        //何個目のmorelinkがクリックされたかをカウント
        index = $(this).index('.morelink');
        //(クリックされたindex +2) * 分割数 = 表示数
        for (k = 0; k < (index + 2) * division; k++) {
            $('.p-news__content__article,.morelink').eq(k).fadeIn();
        }

        //一旦全てのmorelink削除
        $('.morelink').hide();
        //次のmorelink(index+1)を表示
        $('.morelink').eq(index + 1).show();

    });

    // ===========================================================
    // アコーディオンメニュー
    // ===========================================================
    $('.toggle_switch').on('click', function () {
        $(this).toggleClass('open');
        $(this).next('.toggle_contents').slideToggle();
    });

    // ===========================================================
    // モーダルウィンドウ
    // ===========================================================
    $('#openModal').click(function () {
        $('#modalArea').fadeIn();
    });
    $('#closeModal , #modalBg').click(function () {
        $('#modalArea').fadeOut();
    });
});