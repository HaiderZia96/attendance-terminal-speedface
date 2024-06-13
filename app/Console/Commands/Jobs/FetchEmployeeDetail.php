<?php

namespace App\Console\Commands\Jobs;

use App\Models\Manager\Employee;
use App\Models\Manager\GetEmployee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchEmployeeDetail extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-employee-detail';

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


            $employeeId = '111106';
            $response = Http::get("http://localhost/attendance_hub/public/api/employee/$employeeId");
            if ($response->successful()) {
                $employeeDetails = $response->json();
//            dd($employeeDetails['data']);
                $record = Employee::create($employeeDetails['data']);
//         dd($record);
//            $this.info("Cron is working fine!");

            } else {
                $this->error('Something went wrong!' .
                    $response->status());
            }

    }
}
