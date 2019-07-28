@extends('layouts/basic')

@section('title','メイン')
@include('common.head')

@include('common.header')
@section('contents')
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ $userInfo['name'] }}さんの情報
            </div>

            <div class="panel-body">
                <!-- User Profile Contents -->
                <div class="form-horizontal">
                    <!-- profile_banner_url -->
                    <div class="form-group">
                        <strong class="col-sm-3">バナー画像</strong>
                        @if(!empty($userInfo['profile_banner_url']))
                        <img src="{{ $userInfo['profile_banner_url'] }}" width="100" height="50">
                        @endif
                    </div>
                    <!-- profile_image_url -->
                    <div class="form-group">
                        <strong class="col-sm-3">プロフィール画像</strong>
                        <img src="{{ $userInfo['profile_image_url'] }}" width="50" height="50">
                    </div>
                    <!-- Name  -->
                    <div class="form-group">
                        <strong class="col-sm-3">ユーザ名</strong>
                        <div>{{ $userInfo['name'] }}</div>
                    </div>

                    <!-- Screen Name  -->
                    <div class="form-group">
                        <strong class="col-sm-3">スクリーン名</strong>
                        <div>{{ $userInfo['screen_name'] }}</div>
                    </div>

                    <!--  User description  -->
                    <div class="form-group">
                        <strong class="col-sm-3">説明</strong>
                        <div>{{ $userInfo['description'] }}</div>
                    </div>

                    <!--  location  -->
                    <div class="form-group">
                        <strong class="col-sm-3">場所</strong>
                        <div>{{ $userInfo['location'] }}</div>
                    </div>

                    <!--  url  -->
                    <div class="form-group">
                        <strong class="col-sm-3">URL</strong>
                        <a href="{{ $userInfo['url'] }}">qiita</a>
                    </div>

                    <!--  status  -->
                    <div class="form-group">
                        <strong class="col-sm-3">最新のTweet</strong>
                        <div>{{ get_object_vars($userInfo['status'])['text'] }}</div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <a href="/oauthlogout" class="btn btn-primary">ログアウト</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('common.footer')