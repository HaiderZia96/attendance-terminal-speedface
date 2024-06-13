<?php

namespace App\Jobs;

use App\Models\Manager\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class FetchEmployeeDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
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
