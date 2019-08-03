@extends('layouts/basic')

@section('title','ニュース')
@include('common.head')

@include('common.header')
@section('contents')
<main class="main">
    <div class="p-news">
        <h1 class="p-news__title">Crypto News</h1>
        <div class="p-news__content">
            @foreach($news_list as $list)
            <div class="p-news__content__article">
                <div class="p-news__content__article__area">
                    <a href="{{$list['url']}}" class="p-news__content__article__area__describe" target=”_blank” rel=”noopener”>{{$list['title']}}</a>
                </div>
                <div class="p-news__content__article__area">
                    <p class="p-news__content__article__area__upday">更新日：{{$list['updated']}}</p>
                </div>
            </div>
            @endforeach
            <div class="p-news__content__dummy"></div>

        </div>

    </div>

    <div class="p-news__dummy"></div>

</main>
@endsection

@include('common.footer')