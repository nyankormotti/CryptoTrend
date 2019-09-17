<!DOCTYPE html>
<html lang="ja">
@yield('head')

<body>
    @if(\Route::current() -> getName() == 'signup' && count($errors) > 0 || !empty($signError))
    <div class="wrapper--signup--errors">
        @elseif(\Route::current() -> getName() == 'signup')
        <div class="wrapper--signup">

            @elseif(\Route::current() -> getName() == 'login' && count($errors) > 0 || !empty($loginError))
            <div class="wrapper--login--errors">
                @elseif(\Route::current() -> getName() == 'login')
                <div class="wrapper--login">
                    @elseif(count($errors) > 0 || !empty($message))
                    <div class="wrapper--errors">
                        @elseif(\Route::current() -> getName() == 'changeAccount')
                        <div class="wrapper--change">
                            @else
                            <div class="wrapper">
                                @endif
                                @yield('header')

                                @yield('contents')

                            </div>
                            @yield('footer')

</body>

</html>