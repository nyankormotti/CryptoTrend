@extends('layouts/basic')

@section('title','トレンド')
@include('common.head')

@include('common.header')
@section('contents')
<main id="trend_template" class="main">
    <trend></trend>
</main>
<div class="main__dummy"></div>
@endsection

@include('common.footer')