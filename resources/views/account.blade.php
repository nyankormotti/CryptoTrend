@extends('layouts/basic')

@section('title','アカウント')
@include('common.head')

@include('common.header')
@section('contents')
<main id="account_template" class="main">
    <account></account>
</main>

<div class="main__dummy"></div>

@endsection

@include('common.footer')