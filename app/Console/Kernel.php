<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\GetCryptoPriceBatch;
use App\Console\Commands\GetCryptoTweetCountBatch;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetCryptoPriceBatch::class,
        GetCryptoTweetCountBatch::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('batch:CryptoPrice')
            ->daily();
            
        $schedule->command('batch:CryptoTweet')
            ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
