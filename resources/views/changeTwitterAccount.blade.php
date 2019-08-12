@extends('layouts/basic')

@section('title','Twitterアカウント変更')
@include('common.head')

@include('common.header')

@section('contents')
<main>
    <div class="form form__bottom--reset">
        <h2 class="form__title">Twitterアカウント変更</h2>
        <div class="form__content">
            @if(count($errors) > 0 || !empty($message))
            <p class="u-err__msg__main">入力値に問題があります。再入力してください。</p>
            @endif
            <p class="form__content__descript">ご登録されているTwitterアカウントが見つかりません。
                <br>別のTwitterアカウントにてTwitterにログインした後に、
                <br>Twitterアカウントの変更処理を行ってください。
            </p>
            <form action="/changeTwitterAccount" method="post" class="form__block">
                {{ csrf_field() }}
                <div>
                    <label class="textfield__label" for="Screen_name">Twitterアカウント</label>
                </div>
                @if($errors->has('screen_name'))
                <div class="u-err__msg">{{$errors->first('screen_name')}}</div>
                @elseif(!empty($message))
                <div class="u-err__msg">{{$message}}</div>
                @endif
                <div class="textfield__area">
                    <input type="text" class="textfield__input" name="screen_name" placeholder="@の後ろの文字を入力してください。" autocomplete="off" value="{{old('screen_name')}}">
                </div>
                <div class="btn__content btn__form">
                    <input class="btn" type="submit" name="submit" value="変更">
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