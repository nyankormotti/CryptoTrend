@extends('layouts/basic')

@section('title','マイページ')
@include('common.head')

@include('common.header')
@section('contents')

<!-- main -->
<main class="main">
    <div class="mypage">
        <div class="mypage__maintitle">マイページ</div>

        <!-- Twitterアカウント変更 -->
        <section class="mypage__parts mypage__account toggle_wrap">
            @if($errors->has('screen_name'))
            <div class="mypage__title toggle_switch open">
                @else
                <div class="mypage__title toggle_switch">
                    @endif
                    <p class="mypage__title__sub">Twitterアカウント変更</p>
                </div>
                @if($errors->has('screen_name'))
                <form class="form__content toggle_contents" action="" method="post" style="display:block;">
                    @else
                    <form class="form__content toggle_contents" action="" method="post">
                        @endif
                        {{ csrf_field() }}
                        <p class="form__content__descript">新しいTwitterアカウントを入力してください。</p>
                        <div>
                            <label class="textfield__label" for="Screen_name">Twitterアカウント</label>
                        </div>
                        @if($errors->has('screen_name'))
                        <div class="err__msg">{{$errors->first('screen_name')}}</div>
                        @endif
                        <div class="textfield__area">
                            @if($errors->has('screen_name'))
                            <input type="text" class="textfield__input" name="screen_name" autocomplete="off" value="{{old('screen_name')}}">
                            @else
                            <input type="text" class="textfield__input" name="screen_name" autocomplete="off" placeholder="@の後ろの文字を入力してください。">
                            @endif
                        </div>
                        <div class="btn__mypage__user btn__form">
                            <input class="btn" type="submit" name="account_change" value="変更">
                        </div>
                    </form>
        </section>

        <!-- メールアドレス変更 -->
        <section class="mypage__parts mypage__account toggle_wrap">
            @if($errors->has('email'))
            <div class="mypage__title toggle_switch open">
                @else
                <div class="mypage__title toggle_switch">
                    @endif
                    <p class="mypage__title__sub">メールアドレス変更</p>
                </div>
                @if($errors->has('email'))
                <form class="form__content toggle_contents" action="" method="post" style="display:block;">
                    @else
                    <form class="form__content toggle_contents" action="" method="post">
                        @endif
                        {{ csrf_field() }}
                        <p class="form__content__descript">新しいメールアドレスを入力してください。</p>
                        <div>
                            <label class="textfield__label" for="Email">メールアドレス</label>
                        </div>
                        @if($errors->has('email'))
                        <div class="err__msg">{{$errors->first('email')}}</div>
                        @endif
                        <div class="textfield__area">
                            @if($errors->has('email'))
                            <input type="text" class="textfield__input" name="email" autocomplete="off" value="{{old('email')}}">
                            @else
                            <input type="text" class="textfield__input" name="email" autocomplete="off" placeholder="メールアドレスを入力してください。">
                            @endif
                        </div>
                        <div class="btn__mypage__user btn__form">
                            <input class="btn" type="submit" name="email_change" value="変更">
                        </div>
                    </form>
        </section>

        <!-- パスワード変更 -->
        <section class="mypage__parts mypage__account toggle_wrap">
            @if($errors->has('email'))
            <div class="mypage__title toggle_switch open">
                @else
                <div class="mypage__title toggle_switch">
                    @endif
                    <p class="mypage__title__sub">パスワード変更</p>
                </div>
                @if($errors->has('old_pass') || $errors->has('password'))
                <form class="form__content toggle_contents" action="__changePassword" method="post" style="display:block;">
                    @else
                    <form class="form__content toggle_contents" action="__changePassword" method="post">
                        @endif
                        {{ csrf_field() }}
                        <input type="hidden" name="err_dummy">
                        <p class="form__content__descript">パスワードを変更します。<br>
                            現在のパスワードと新しいパスワードを<br class="mypage__descropt__line">入力してください。</p>
                        <div>
                            <label class="textfield__label" for="Password">現在のパスワード</label>
                        </div>
                        @if($errors->has('old_pass'))
                        <div class="err__msg">{{$errors->first('old_pass')}}</div>
                        @endif
                        <div class="textfield__area">
                            <input type="password" class="textfield__input" name="old_pass" placeholder="パスワードを入力してください。">
                        </div>
                        <div>
                            <label class="textfield__label" for="Password">新しいパスワード</label><span>&nbsp;&nbsp;&nbsp;※半角英数字8桁以上</span>
                        </div>
                        @if($errors->has('password'))
                        <div class="err__msg">{{$errors->first('password')}}</div>
                        @endif
                        <div class="textfield__area">
                            <input type="password" class="textfield__input" name="password" placeholder="パスワードを入力してください。">
                        </div>
                        <div>
                            <label class="textfield__label" for="Password">新しいパスワード(確認)</label>
                        </div>
                        <div class="textfield__area">
                            <input type="password" class="textfield__input" name="password_confirmation" placeholder="パスワードを入力してください。">
                        </div>
                        <div class="btn__mypage__user btn__form">
                            <input class="btn" type="submit" name="submit" value="送信">
                        </div>
                    </form>
        </section>

        <!-- お問い合わせ -->
        <section class="mypage__parts mypage__account toggle_wrap">
            @if($errors->has('email'))
            <div class="mypage__title toggle_switch open">
                @else
                <div class="mypage__title toggle_switch">
                    @endif
                    <p class="mypage__title__sub">お問い合わせ</p>
                </div>
                @if($errors->has('old_pass') || $errors->has('password'))
                <form class="form__content toggle_contents" action="contact" method="post" style="display:block;">
                    @else
                    <form class="form__content toggle_contents" action="contact" method="post">
                        @endif
                        {{ csrf_field() }}
                        <div>
                            <label class="textfield__label" for="name">お名前</label>
                        </div>
                        @if($errors->has('email'))
                        <div class="err__msg">{{$errors->first('name')}}</div>
                        @endif
                        <div class="textfield__area">
                            <input type="text" class="textfield__input" name="name" placeholder="15文字以内にて入力してください。" autocomplete="off" value="{{old('name')}}">
                        </div>
                        <div>
                            <label class="textfield__label" for="Email">メールアドレス</label>
                        </div>
                        @if($errors->has('email'))
                        <div class="err__msg">{{$errors->first('email')}}</div>
                        @endif
                        <div class="textfield__area">
                            <input type="text" class="textfield__input" name="email" placeholder="メールアドレスを入力してください。" autocomplete="off" value="{{old('email')}}">
                        </div>
                        <div>
                            <label class="textfield__label" for="Comment">お問い合わせ内容</label>
                        </div>
                        @if($errors->has('comment'))
                        <div class="err__msg">{{$errors->first('comment')}}</div>
                        @endif
                        <div class="textfield__comment__area">
                            <textarea class="textfield__comment" name="comment" id="" cols="30" rows="10" placeholder="1000文字以内にて入力してください。" value="{{old('comment')}}"></textarea>
                        </div>
                        <div class="btn__contact btn__form">
                            <input class="btn" type="submit" name="submit" value="送信">
                        </div>
                    </form>
        </section>

        <!-- 退会 -->
        <section class="mypage__parts mypage__with toggle_wrap">
            <div class="mypage__title toggle_switch">
                <p class="mypage__title__sub">退会</p>

            </div>
            <div class="form__content toggle_contents" action="__withdraw" method="post">

                <p class="form__descript withdraw__descropt">退会する場合は、<br class="mypage__descropt__line">下記のボタンを押してください。</p>

                <div class="btn__mypage__user btn__form">
                    <button id="openModal" class="btn" name="submit">退会</button>
                </div>
            </div>

        </section>
    </div>

    <!-- モーダルエリアここから -->
    <section id="modalArea" class="modal__Area">
        <div id="modalBg" class="modal__Bg"></div>
        <form class="modal__Wrapper" action="__withdraw" method="post">
            {{ csrf_field() }}
            <div class="modal__Contents">
                <p class="modal__state">本当に退会しますか？</p>
                <div class="modal__btn__part">
                    <div class="btn__mypage__user btn__form">
                        <input id="btn--withdraw__btn" class="btn withdraw__btn" type="submit" name="submit" value="退会">
                    </div>
                    <div class="btn__mypage__user btn__form">
                        <div id="closeModal" class="btn modal__btn">閉じる</div>
                    </div>
                </div>

            </div>
            <div id="closeModal" class="closeModal">
                ×
            </div>
        </form>
    </section>
    <!-- モーダルエリアここまで -->

    <div class="mypage__dummy"></div>
</main>


@endsection

@include('common.footer')