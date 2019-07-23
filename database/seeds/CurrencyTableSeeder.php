<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 銘柄一覧
        $currency = array(
            '0' => '$BTC',
            '1' => '$ETH',
            '2' => '$ETC',
            '3' => '$LSK',
            '4' => '$FCT',
            '5' => '$XRP',
            '6' => '$XEM',
            '7' => '$LTC',
            '8' => '$BCH',
            '9' => '$MONA',
        );

        for ($i = 0; $i < count($currency); $i++) {

            DB::table('currencies')->insert([
                'currency_name' => $currency[$i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        
    }
}
