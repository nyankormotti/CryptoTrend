@section('footer')
<footer class="footer">
    Copyright nyanko_R_motti .All Rights Reserved
</footer>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>

@if(\Route::current() -> getName() == 'trend')
<script src="{{ asset('js/trend.js') }}"></script>
@elseif(\Route::current() -> getName() == 'account')
<script src="{{ asset('js/account.js') }}"></script>
@elseif(\Route::current() -> getName() == 'news')
<script src="{{ asset('js/news.js') }}"></script>
@endif
@endsection