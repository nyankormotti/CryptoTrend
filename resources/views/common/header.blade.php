@section('header')
<header class="header">
    <div class="header__section">
        <h1 class="header__section__title">
            CryptoTrend
        </h1>

        <div class="menu__trigger js-toggle-sp--menu">
            <span></span>
            <span></span>
            <span></span>
        </div>


        <nav class="nav__menu js-toggle-sp--menu-target">
            <ul class="menu">
                @if(!Auth::check())
                <li class="menu__item"><a href="/" class="menu__link">トップ</a></li>
                <li class="menu__item"><a href="signup" class="menu__link menu__link--color">会員登録</a></li>
                <li class="menu__item"><a href="login" class="menu__link">ログイン</a></li>
                @else
                <li><a href="trend" class="menu__link menu__link--hover">トレンド</a></li>
                <li><a href="account" class="menu__link menu__link--hover">関連アカウント</a></li>
                <li><a href="news" class="menu__link menu__link--hover">ニュース</a></li>
                <li><a href="mypage" class="menu__link menu__link--hover">マイページ</a></li>
                <li><a href="oauthlogout" class="menu__link menu__link--hover">ログアウト</a></li>
                @endif
            </ul>
        </nav>
    </div>
</header>

<div class="header--dummy"></div>

@endsection