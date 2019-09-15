@extends('layouts/basic')

@section('title','トップ')
@include('common.head')

@include('common.header')

@section('contents')

<!-- トップバナー -->
<section class="p-banner">
    <h1 class="p-banner__title">
        <span class="p-banner__title--sub">仮想通貨トレンド分析システム</span>
        <br><span class="p-banner__title--main">CryptoTrend</span></h1>
</section>

<!-- ABOUT -->
<section class="p-about">
    <div class="p-about__area">
        <h2 class="p-about__area__title">CryptoTrendとは</h2>
        <div class="p-about__area__under"><span class="p-about__area__under__line"></span></div>
        <div class="p-about__area__describe">
            <p class="p-about__area__describe__main">仮想通貨のトレンドをいち早くキャッチできるよう<br>あなたをサポートいたします！</p>
        </div>
    </div>
</section>

<!-- DETAIL -->
<section class="p-detail">
    <div class="p-detail__area">
        <div class="p-detail__panel p-detail__panel--describe">
            <h2 class="p-detail__panel__title">話題性を<br class="p-detail__panel__title--br">ランキング！！</h2>
            <div class="p-detail__panel__describe">
                <p class="p-detail__panel__describe__area">
                    Twitter上で呟かれた仮想通貨のツイート数を銘柄毎に集計し、ランキング表示します！
                    <br>
                    <br>※取扱銘柄は、BTC、ETH、ETC、LSK、FCT、XRP、XEM、LTC、BCH、MONAの10種類です。
                </p>
            </div>
        </div>
        <div class="p-detail__panel p-detail__panel--img">
            <img class="p-detail__panel__img" src="{{ asset('images/top_panel_1.png') }}" alt="パネル画像1">
        </div>
    </div>
</section>

<section class="p-detail">
    <div class="p-detail__area">
        <div class="p-detail__panel">
            <img class="p-detail__panel__img" src="{{ asset('images/top_panel_2.png') }}" alt="パネル画像1">
        </div>
        <div class="p-detail__panel  p-detail__panel--describe">
            <h2 class="p-detail__panel__title">アカウントを<br class="p-detail__panel__title--br">自動フォロー！！</h2>
            <div class="p-detail__panel__describe">
                <p class="p-detail__panel__describe__area">
                    「仮想通貨」キーワードを含むのTwitterアカウントを自動フォローできます！
                    <br>もちろんサイト上で選んで個別にフォローも可能です！！
                    <br>
                    <br>※フォローは個別・自動含めて、1日25回までの制限です。
                </p>
            </div>
        </div>
    </div>
</section>

<section class="p-detail">
    <div class="p-detail__area">
        <div class="p-detail__panel  p-detail__panel--describe">
            <h2 class="p-detail__panel__title">関連ニュースを<br class="p-detail__panel__title--br">リアルタイムで確認！！</h2>
            <div class="p-detail__panel__describe">
                <p class="p-detail__panel__describe__area">
                    最新の仮想通貨関連のニュースをリアルタイムで取得し、サイト上で確認できます！
                    <br>
                    <br>※Googleニュースより最新の100件を取得します。

                </p>
            </div>
        </div>
        <div class="p-detail__panel">
            <img class="p-detail__panel__img" src="{{ asset('images/top_panel_3.png') }}" alt="パネル画像1">
        </div>
    </div>
</section>

<!-- 会員登録、ログインボタン -->
<section class="p-entrance">
    <section class="p-entrance__content">
        <div class="p-entrance__panel">
            <h2 class="p-entrance__panel__title">初めての方はこちら</h2>
            <a href="signup" class="p-entrance__panel__btn signup__btn">会員登録</a>
        </div>
        <div class="p-entrance__panel">
            <h2 class="p-entrance__panel__title">会員の方はこちら</h2>
            <a href="login" class="p-entrance__panel__btn login__btn">ログイン</a>
        </div>
    </section>
</section>

<!-- お問い合わせフォーム -->
@if(count($errors) > 0)
<section class="p-contact js-u-err__msg__main">
    @else
    <section class="p-contact">
        @endif
        <div class="form form__contact">
            <h2 class="form__title">お問い合わせ</h2>
            <div class="form__content">
                @if(count($errors) > 0)
                <p class="u-err__msg__main">入力値に問題があります。再入力してください。</p>
                @endif
                <p class="form__content__descript">メールアドレス、内容をご記載の上、お問い合わせください。</p>
                <form action="contact" method="post" class="form__content__block">
                    {{ csrf_field() }}
                    <div>
                        <label class="textfield__label" for="Email">メールアドレス</label>
                    </div>
                    @if($errors->has('email'))
                    <div class="u-err__msg">{{$errors->first('email')}}</div>
                    @endif
                    <div class="textfield__area">
                        <input type="text" class="textfield__input" name="email" placeholder="メールアドレスを入力してください。" autocomplete="off" value="{{old('email')}}">
                    </div>
                    <div>
                        <label class="textfield__label" for="Comment">お問い合わせ内容</label>
                    </div>
                    @if($errors->has('comment'))
                    <div class="u-err__msg">{{$errors->first('comment')}}</div>
                    @endif
                    <div class="textfield__comment__area">
                        @if(count($errors) > 0)
                        <textarea class="textfield__comment" name="comment" id="" cols="30" rows="10" placeholder="1000文字以内にて入力してください。">{{old('comment')}}</textarea>
                        @else
                        <textarea class="textfield__comment" name="comment" id="" cols="30" rows="10" placeholder="1000文字以内にて入力してください。" value="{{old('comment')}}"></textarea>
                        @endif
                    </div>
                    <div class="btn__contact btn__form">
                        <input class="btn" type="submit" name="submit" value="送信">
                    </div>
                </form>
            </div>
        </div>
    </section>

    @endsection

    @include('common.footer')