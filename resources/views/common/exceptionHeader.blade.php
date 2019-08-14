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
                <li class="menu__item"><a href="/" class="menu__link">トップ</a></li>
                <li class="menu__item"><a href="signup" class="menu__link menu__link--color">会員登録</a></li>
                <li class="menu__item"><a href="login" class="menu__link">ログイン</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="header--dummy"></div>

@endsection