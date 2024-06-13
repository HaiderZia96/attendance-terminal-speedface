<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProfileService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:profile-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $profile = new \App\Services\ProfileService();
        $profile->handle();
        die();
    }
}
