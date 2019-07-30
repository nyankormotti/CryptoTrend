@extends('layouts/basic')

@section('title','トレンド')
@include('common.head')

@include('common.header')
@section('contents')
<main class="main">
    <section class="main__base">
        <section class="p-trend">
            <div class="p-trend__top">
                <h2 class="p-trend__top__title">Crypto Ranking</h2>
                <span class="p-trend__top__border"></span>
            </div>
            <div class="p-trend__main">
                <table class="p-table">
                    <thead class="p-table__thead">
                        <tr class="p-table__thead__tr">
                            <th class="p-table__thead__tr__th">順位</th>
                            <th class="p-table__thead__tr__th">銘柄</th>
                            <th class="p-table__thead__tr__th">ツイート数</th>
                            <th class="p-table__thead__tr__th">最高取引
                                <br>価格(JPY)</th>
                            <th class="p-table__thead__tr__th">最安取引
                                <br>価格(JPY)</th>
                        </tr>
                    </thead>
                    <tbody class="p-table__tbody">
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">1位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link p-table__tbody__tr__td__link--gold">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">1120292</td>
                            <td class="p-table__tbody__tr__td">1049008</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">2位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link p-table__tbody__tr__td__link--silver">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">3位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link p-table__tbody__tr__td__link--blonze">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">4位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">5位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">6位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">7位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">8位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">9位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                        <tr class="p-table__tbody__tr">
                            <td class="p-table__tbody__tr__td">10位</td>
                            <td class="p-table__tbody__tr__td">
                                <a href="https://twitter.com/search?q=$BTC&src=recent_search_click" class="p-table__tbody__tr__td__link">$BTC</a>
                            </td>
                            <td class="p-table__tbody__tr__td">356</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                            <td class="p-table__tbody__tr__td">不明</td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-note">
                    <p class="p-note__describe">※銘柄名をクリックするとtwitter検索画面が表示されます。</p>
                    <p class="p-note__describe">※取引価格は24時間当たりの価格を示しています。</p>
                </div>
            </div>
        </section>
        <section class="p-sidebar">
            <div class="p-sidebar__top">
                <h2 class="p-sidebar__top__title">Search</h2>
                <span class="p-sidebar__top__border"></span>
            </div>
            <div class="p-sidebar__period">
                <h3 class="p-sidebar__period__title">期間</h3>
                <div class="p-sidebar__period__area">
                    <div class="c-action-btn">1時間</div>
                    <div class="c-action-btn">1日間</div>
                    <div class="c-action-btn">1週間</div>
                </div>
            </div>
            <div class="p-sidebar__brand">
                <h3 class="p-sidebar__brand__title">銘柄</h3>
                <div class="p-sidebar__brand__area">
                    <div class="c-action-btn">$BTC</div>
                    <div class="c-action-btn">$ETH</div>
                    <div class="c-action-btn">$ETC</div>
                    <div class="c-action-btn">$LSK</div>
                    <div class="c-action-btn">$FCT</div>
                    <div class="c-action-btn">$XRP</div>
                    <div class="c-action-btn">$XEM</div>
                    <div class="c-action-btn">$LTC</div>
                    <div class="c-action-btn">$BCH</div>
                    <div class="c-action-btn">$MONA</div>
                </div>
            </div>
        </section>

    </section>
</main>

<div class="main__dummy"></div>

@endsection

@include('common.footer')