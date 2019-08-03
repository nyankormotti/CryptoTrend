$(function(){

    // ボタンを押した時の挙動
    // $('.c-action-btn').on('click',function(){
    //     let btn = $(this);
    //     btn.toggleClass('c-action-btn--blue');
    // });

    // ===========================================================
    // アカウントプロフィールの文字列が長い時「…」を末尾につける処理
    longString($('.p-user__text__profile__describe'),55);
    // 最新のチートの文字列が長い時「…」を末尾につける処理
    longString($('.p-user__text__tweet__describe'),85);


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
  


    // ============================関数=============================
    // ===========================================================
    // 文字列が長い時「…」を末尾につける処理
    // ===========================================================
    function longString($setElm, cutFigure) {
        // let cutFigure = '60'; // 表示する文字数
        let afterTxt = ' …'; // 文字カット後に表示するテキスト

        $setElm.each(function () {
            let textLength = $(this).text().length;  // 文字数を取得
            let textTrim = $(this).text().substr(0, (cutFigure)) // 表示する数以上の文字をトリムする

            if (cutFigure < textLength) { // 文字数が表示数より多い場合
                $(this).html(textTrim + afterTxt).css({ visibility: 'visible' }); // カット後の文字数に…を追加
            } else if (cutFigure >= textLength) {// 文字数が表示数以下の場合
                $(this).css({ visibility: 'visible' }); // そのまま表示
            }
        });
    }
});