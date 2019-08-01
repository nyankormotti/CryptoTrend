@extends('layouts/basic')

@section('title','アカウント')
@include('common.head')

@include('common.header')
@section('contents')
<main class="main">
    <section class="main__base">
        <section class="p-account">
            <div class="p-account__top">
                <div class="p-account__top__area">
                    <h2 class="p-account__top__area__title">Crypto Account</h2>
                    <p class="p-account__top__area__page">0 - 20 / 300</p>
                </div>
                <span class="p-account__top__border"></span>
            </div>
            <div class="p-account__main">
                <!-- 1こめ -->
                <div class="p-user">
                    <div class="p-user__top">
                        <h2 class="p-user__top__name">仮想通貨マンあああああああああああああああああああああ</h2>
                        <div class="p-user__top__btn c-action-btn">フォロー</div>
                    </div>
                    <div class="p-user__status">
                        <p class="p-user__status__screen">@kasoutu_ka1</p>
                        <p class="p-user__status__fcount">1200 フォロー | 1000 フォロワー</p>
                    </div>
                    <div class="p-user__text">
                        <div class="p-user__text__profile">
                            <p class="p-user__text__profile__describe">仮想通貨はじめました！！！！ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</p>
                        </div>
                        <span class="p-user__text__border"></span>
                        <div class="p-user__text__tweet">
                            <h3 class="p-user__text__tweet__title">最新ツイート</h3>
                            <p class="p-user__text__tweet__describe">あああああ！仮想通貨暴落したlあぁぁlああl！！！あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</p>
                        </div>
                    </div>
                </div>
                <!-- 2こめ -->
                <div class="p-user">
                    <div class="p-user__top">
                        <h2 class="p-user__top__name">仮想通貨マンあああああああああああああああああああああ</h2>
                        <div class="p-user__top__btn c-action-btn">フォロー</div>
                    </div>
                    <div class="p-user__status">
                        <p class="p-user__status__screen">@kasoutu_ka1</p>
                        <p class="p-user__status__fcount">1200 フォロー | 1000 フォロワー</p>
                    </div>
                    <div class="p-user__text">
                        <div class="p-user__text__profile">
                            <p class="p-user__text__profile__describe">仮想通貨はじめました！！！！ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</p>
                        </div>
                        <span class="p-user__text__border"></span>
                        <div class="p-user__text__tweet">
                            <h3 class="p-user__text__tweet__title">最新ツイート</h3>
                            <p class="p-user__text__tweet__describe">あああああ！仮想通貨暴落したlあぁぁlああl！！！あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</p>
                        </div>
                    </div>
                </div>
                <!-- 3こめ -->
                <div class="p-user">
                    <div class="p-user__top">
                        <h2 class="p-user__top__name">仮想通貨マンあああああああああああああああああああああ</h2>
                        <div class="p-user__top__btn c-action-btn">フォロー</div>
                    </div>
                    <div class="p-user__status">
                        <p class="p-user__status__screen">@kasoutu_ka1</p>
                        <p class="p-user__status__fcount">1200 フォロー | 1000 フォロワー</p>
                    </div>
                    <div class="p-user__text">
                        <div class="p-user__text__profile">
                            <p class="p-user__text__profile__describe">仮想通貨はじめました！！！！ああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</p>
                        </div>
                        <span class="p-user__text__border"></span>
                        <div class="p-user__text__tweet">
                            <h3 class="p-user__text__tweet__title">最新ツイート</h3>
                            <p class="p-user__text__tweet__describe">あああああ！仮想通貨暴落したlあぁぁlああl！！！あああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああああ</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="p-account__page">ページネーション</div>
        </section>
        <section class="p-sidebar p-sidebar--option">
            <div class="p-sidebar__top">
                <h2 class="p-sidebar__top__title">Option</h2>
                <span class="p-sidebar__top__border"></span>
            </div>
            <div class="p-sidebar__limit">
                <h3 class="p-sidebar__limit__title">上限回数</h3>
                <div class="p-sidebar__limit__area">
                    <div class="p-sidebar__limit__area--f_action">
                        <h3 class="p-sidebar__limit__area--f_action--title">フォロー</h3>
                        <p class="p-sidebar__limit__area--f_action--count"><span class="p-sidebar__limit__area--f_action--count--now">0</span> / 25</p>
                    </div>
                    <div class="p-sidebar__limit__area--f_action">
                        <h3 class="p-sidebar__limit__area--f_action--title">フォロー解除</h3>
                        <p class="p-sidebar__limit__area--f_action--count"><span class="p-sidebar__limit__area--f_action--count--now">0</span> / 100</p>
                    </div>
                </div>
            </div>
            <div class="p-sidebar__follow">
                <h3 class="p-sidebar__follow__title">表示形式</h3>
                <div class="p-sidebar__follow__area">
                    <div class="c-action-btn c-action-btn--follow">未フォロー</div>
                    <div class="c-action-btn c-action-btn--follow">フォロー済</div>
                </div>
            </div>

            <div class="p-sidebar__follow">
                <h3 class="p-sidebar__follow__title">自動フォロー</h3>
                <div class="p-sidebar__follow__area">
                    <div class="c-action-btn c-action-btn--follow">ON</div>
                    <div class="c-action-btn c-action-btn--follow">OFF</div>
                </div>
            </div>
        </section>
    </section>
</main>

<div class="main__dummy"></div>

@endsection

@include('common.footer')