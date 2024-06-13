<?php

namespace App\Console\Commands;

use App\Services\AttendanceServiceV2;
use Illuminate\Console\Command;

class AttendanceService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:attendance-service';

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
        $erpAttendance = new AttendanceServiceV2();
        $erpAttendance->handle();
        die();
    }
}
