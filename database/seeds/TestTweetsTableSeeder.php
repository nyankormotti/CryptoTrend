<?php


use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestTweetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $nowDate = new Carbon();
        $acquisition_date = (substr($nowDate->subDay(7), 0, 13)) . ":00:00";
        
        for($i = 0; $i < 168; $i++) {
            for ($j = 1; $j <= 10; $j++) {
                DB::table('tweets')->insert([
                    'currency_id' => $j,
                    'tweet_count' => mt_rand(1, 5000),
                    'acquisition_date' => $acquisition_date
                ]);
            }
            $acquisition_date = (substr($nowDate->addHour(), 0, 13)) . ":00:00";
        }
    }
}
