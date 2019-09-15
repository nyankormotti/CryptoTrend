@section('head')

<head>
    <meta charset="UTF-8">
    @if(\Route::current() -> getName() == '')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！各銘柄の話題性を集計しランキング化！リアルタイムで最新の仮想通貨関連のニュースも取得！また関連のあるTwitterアカウントを自動でフォローできる機能あり！">
    @elseif(\Route::current() -> getName() == 'login')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！入力されたアカウント情報を元に、ログイン認証を実施します！">
    @elseif(\Route::current() -> getName() == 'signup')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！ご自身のTwitterアカウント情報を元にサービスをご利用できます！Twitterアカウントと一緒に会員登録をお願いいたします！">
    @elseif(\Route::current() -> getName() == 'passRemindSend')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！ご登録されたメールアドレスを元にパスワード再発行の認証キーを発行いたします！">
    @elseif(\Route::current() -> getName() == 'passRemindRec')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！認証キーを元にパスワードを再発行いたします。">
    @elseif(\Route::current() -> getName() == 'withDraw')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！退会手続きの完了をお知らせします。">
    @elseif(\Route::current() -> getName() == 'changeAccount')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！Twitterアカウントを変更いたします。">
    @elseif(\Route::current() -> getName() == 'trend')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！仮想通貨銘柄の話題性を集計しランキング表示いたします！">
    @elseif(\Route::current() -> getName() == 'account')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！twitterの仮想通貨関連アカウントに対し、個別または自動でフォローが可能です！">
    @elseif(\Route::current() -> getName() == 'news')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！Googleニュースより「仮想通貨」関連の最新100件のニュースを取得します！">
    @elseif(\Route::current() -> getName() == 'mypage')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！ご登録されたユーザー情報の変更、またお問い合わせの処理が実施できます！">
    @elseif(\Route::current() -> getName() == 'exception')
    <meta name="description" content="仮想通貨のトレンドをいち早くキャッチできるサービスです！障害が発生しております。申し訳ありませんが、しばらく経ってからご利用ください。">
    @endif
    <meta name=”keywords” content=”仮想通貨,トレンド,CryptoTrend,種類,仮想通貨ニュース>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')&nbsp;|&nbsp;CryptoTrend</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
@endsection