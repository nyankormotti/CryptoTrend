<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CurrencyTableSeeder::class);
        $this->call(TrendTableSeeder::class);
        // テストデータ作成
        $this->call(TestTweetsTableSeeder::class);
        $this->call(TestTrendsTableSeeder::class);
    }
}
