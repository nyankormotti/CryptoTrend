<?php

namespace App\Console\Commands;

use App\Currency;
use Illuminate\Console\Command;
use App\Libraries\CommonFunctions;

class GetCryptoPriceBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:CryptoPrice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '各銘柄の取引価格を取得';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        \Log::info('===============');
        \Log::info('各銘柄の取引価格取得バッチ開始');

        // coincheck API Tickerにて銘柄の取引価格を取得
        // coincheckのTickerではBTCの取引価格のみ取得可能
        // そのため、CurrenciesテーブルのBTCのレコードにのみ、取引価格の値を更新する。
        $commonFunc = new CommonFunctions;
        $rate = $commonFunc->coincheck();

        $currency = Currency::find(1);
        $currency->max_price = $rate['high'];
        $currency->min_price = $rate['low'];
        $currency->save();

        \Log::info('各銘柄の取引価格取得バッチ終了');
        \Log::info('===============');
    }
}
