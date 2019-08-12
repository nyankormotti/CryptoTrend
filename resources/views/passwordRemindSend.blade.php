@extends('layouts/basic')

@section('title','パスワードリマインド')
@include('common.head')

@include('common.header')

@section('contents')
<main>
    <div class="form form__bottom--reset">
        <h2 class="form__title">パスワードリマインダー</h2>
        <div class="form__content">
            @if(count($errors) > 0)
            <p class="u-err__msg__main">入力値に問題があります。再入力してください。</p>
            @elseif(!empty($message))
            <p class="u-err__msg__main">{{$message}}</p>
            @endif
            <p class="form__content__descript">ご指定いただいたメールアドレス宛に、
                <br>パスワード再発行用の認証キーを送付します。</p>
            <form action="/passwordRemindSend" method="post" class="form__block">
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
                <div class="btn__content btn__form">
                    <input class="btn" type="submit" name="submit" value="送信">
                </div>
                <p class="link__content">
                    <a class="link" href="/">&gt;&gt;トップページ</a>
                </p>
            </form>
        </div>
    </div>
</main>

@endsection

@include('common.footer')