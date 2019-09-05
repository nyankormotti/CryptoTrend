@section('head')

<head>
    <meta charset="UTF-8">
    @if(\Route::current() -> getName() == '')
    <meta name="description" content="仮想通貨の銘柄ごとのツイート数を集計し、各銘柄の話題性をお伝えします。
    また、Twitterの仮想通貨関連のアカウントを一覧表示し、注目度の高いアカウントをフォローできるサービスです。">
    @endif
    <meta name=”keywords” content=”仮想通貨,トレンド,CryptoTrend,分析,システム”>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')&nbsp;|&nbsp;CryptoTrend</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
@endsection