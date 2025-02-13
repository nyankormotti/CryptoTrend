<?php

namespace App\Console\Commands;

use App\Currency;
use Illuminate\Console\Command;
use App\Libraries\CommonFunctions;
use Illuminate\Support\Facades\DB;

/**
 * 各銘柄の「24時間当たりの最高取引価格」と「24時間当たりの最安取引価格」を取得し、currenciesテーブルを更新
 * （coincheckのTickerではBTCの取引価格のみ取得可能、そのため、CurrenciesテーブルのBTCのレコードにのみ、取引価格の値を更新する。）
 */
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

        DB::beginTransaction();
        try {
            $currency = Currency::find(1);
            $currency->max_price = $rate['high'];
            $currency->min_price = $rate['low'];
            $currency->save();
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }

        \Log::info('各銘柄の取引価格取得バッチ終了');
        \Log::info('===============');
    }
}
