@extends('layouts/basic')

@section('title','Twitterアカウント変更')
@include('common.head')

@include('common.exceptionHeader')

@section('contents')

<main>
    <div class="p-exception">
        <h2 class="p-exception__title">例外発生</h2>
        <div class="p-exception__content">
            <p class="p-exception__content__descript">問題が発生しました。<br>恐れ入りますが、しばらく経ってからご利用ください。</p>
            <p class="p-exception__content__link">
                <a class="link" href="/">&gt;&gt;トップページ</a>
            </p>
        </div>
    </div>
</main>

@endsection

@include('common.footer')