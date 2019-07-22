<?php

namespace App\Console\Commands;

use App\Trend;
use Illuminate\Console\Command;

class TestBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'お試しバッチ処理';

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
        $trend = new Trend();
        // DB::trend($trend)->where('email', '=', 'sample@example.com';)->get();
        $trend->email = 'sample@example.com';
        $trend->save();
    }
}
