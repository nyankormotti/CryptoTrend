@extends('layouts/basic')

@section('title','退会完了')
@include('common.head')

@include('common.header')
@section('contents')
<main>
    <div class="p-withdrawDone">
        <h2 class="p-withdrawDone__title">退会完了</h2>
        <div class="p-withdrawDone__content">
            <p class="p-withdrawDone__content__descript">退会手続きが完了しました</p>
            <p class="p-withdrawDone__content__link">
                <a class="link" href="signup">&gt;&gt;会員登録ページ</a>
            </p>
        </div>
    </div>
</main>

@endsection

@include('common.footer')