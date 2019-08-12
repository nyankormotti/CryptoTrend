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
            <p class="form__content__descript">受信されたメールに記載のある認証キーをご記載ください。
                <br>メールにて再発行したパスワードをお伝えします。</p>
            <form action="/passwordRemindRecieve" method="post" class="form__block">
                {{ csrf_field() }}
                <div>
                    <label class="textfield__label" for="Auth_Key">認証キー</label>
                </div>
                @if($errors->has('auth_key'))
                <div class="u-err__msg">{{$errors->first('auth_key')}}</div>
                @endif
                <div class="textfield__area">
                    <input type="text" class="textfield__input" name="auth_key" placeholder="認証キーを入力してください。" autocomplete="off">
                </div>
                <div class="btn__content btn__form">
                    <input class="btn" type="submit" name="submit" value="送信">
                </div>
                <p class="link__content">
                    <a class="link" href="/passwordRemindSend">&gt;&gt;パスワード再発行メールを再度送付する</a>
                </p>
            </form>
        </div>
    </div>
</main>

@endsection

@include('common.footer')