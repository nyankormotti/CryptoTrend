@extends('layouts/basic')

@section('title','トップ')
@include('common.head')

@include('common.header')

@section('contents')

<!-- トップバナー -->
<section class="p-banner">
    <img class="p-banner__img" src="{{ asset('images/top_banner.jpg') }}" alt="トップ画像">
    <h1 class="p-banner__title">仮想通貨トレンド分析システム
        <br><span class="p-banner__title--main">CryptoTrend</span></h1>
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
            <a href="authLogin" class="p-entrance__panel__btn login__btn">ログイン</a>
        </div>
    </section>
</section>

<!-- お問い合わせフォーム -->
<section class="p-contact">
    <div class="form form__contact">
        <h2 class="form__title">お問い合わせ</h2>
        <div class="form__content">
            <p class="form__content__descript">メールアドレス、内容をご記載の上、お問い合わせください。</p>
            <form action="contact" method="post" class="form__content__block">
                {{ csrf_field() }}
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
        </div>
    </div>
</section>

@endsection

@include('common.footer')