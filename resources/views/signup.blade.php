@extends('layouts/basic')

@section('title','会員登録')
@include('common.head')

@include('common.header')

@section('contents')
<main>
    <div class="form form__signup">
        <h2 class="form__title">会員登録</h2>
        <div class="form__content">
            @if(count($errors) > 0)
            <p class="u-err__msg__main">入力値に問題があります。再入力してください。</p>
            @elseif(!empty($message))
            <p class="u-err__msg__main">{{$message}}</p>
            @endif
            <p class="form__content__descript">会員登録されるユーザー様お一人につき、
                <br>twitterアカウントを1つ登録いたします。
                <br>ご登録されるtwitterアカウントにてTwitterにログインし、
                <br>会員登録を行ってください。</p>
            <form action="/signup" method="post" class="form__block">
                {{ csrf_field() }}
                <div>
                    <label class="textfield__label" for="Screen_name">Twitterアカウント</label>
                </div>
                @if($errors->has('screen_name'))
                <div class="u-err__msg">{{$errors->first('screen_name')}}</div>
                @endif
                <div class="textfield__area">
                    <input type="text" class="textfield__input" name="screen_name" placeholder="@の後ろの文字を入力してください。" autocomplete="off" value="{{old('screen_name')}}">
                </div>
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
                    <label class="textfield__label" for="Password">パスワード</label>
                </div>
                @if($errors->has('password'))
                <div class="u-err__msg">{{$errors->first('password')}}</div>
                @endif
                <div class="textfield__area">
                    <input type="password" class="textfield__input" name="password" placeholder="パスワードを入力してください。">
                </div>
                <div>
                    <label class=" textfield__label" for="Password">パスワード(確認)</label>
                </div>
                @if($errors->has('password_re'))
                <div class="u-err__msg">{{$errors->first('password_confirmation')}}</div>
                @endif
                <div class="textfield__area">
                    <input type="password" class="textfield__input" name="password_confirmation" placeholder="パスワードを入力してください。">
                </div>
                <div class="btn__form">
                    <input class="btn" type="submit" name="submit" value="会員登録">
                </div>
            </form>
        </div>
    </div>
</main>


@endsection

@include('common.footer')