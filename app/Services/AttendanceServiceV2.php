<?php

namespace App\Services;

use App\Models\Manager\Attendance;
use App\Models\Manager\Config;
use App\Models\Manager\SetAttendanceHistory;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AttendanceServiceV2
{

    public function handle(){

        $numberOfAttendanceRecordsSendToHub = 50; // Number of attendance records send in each request.
        $appUser = User::select('*')->where('email','=', 'app@app.com')->first();
        $baseUrl = \config('values.hub');
        $token = \config('values.token');
        $machineIp = \config('values.machine_ip');

        // 1. Get attendance
        $attendances = $this->getAttendance($numberOfAttendanceRecordsSendToHub);

        // 2. Make Log
        $log = $this->createLog($attendances, $appUser, $machineIp);

        // 3. Send to HUB
        $hubResponse = $this->sentToHub($log, $baseUrl, $token);

        // 4. Update Attendance Sync Status
        if(isset($hubResponse["data"]["data"]["attendance_marking"])){
            $this->setAttendanceSync($hubResponse, $appUser);
        }

        // 5. Update Log
        $this->updateLog($hubResponse, $appUser);

        return true;
    }

    public function getAttendance($returnRecords){

        $attendances = Attendance::select('attendances.*','employees.role_status as role_status')
            ->leftJoin('employees', 'employees.employee_code', '=', 'attendances.employee_code')
            ->where('attendances.sync', '!=', 1)
            ->orderBy('attendances.id','desc')
            ->limit($returnRecords)
            ->get();

        return $attendances;
    }

    public function createLog($attendances, $appUser, $machineIp){

        $attendance_arr = array();
        foreach ($attendances as $attendance){
            $arr = [
                'uuid' => Str::uuid()->toString(),
                'employee_code' => $attendance['employee_code'],
                'created_by' => $appUser->id,
            ];
            $log = SetAttendanceHistory::create($arr);

            $attnArr = array(
              "attendance_id" => $attendance['id'],
              "uuid" => $log->uuid,
              "code" => $attendance['employee_code'],
              "punch_time" => $attendance['punch_time'],
              "terminal_ip" => $machineIp,
              "sub_terminal_ip" => $attendance['terminal_alias'],
              "in_out" => $attendance['in_out'],
              "role" => $attendance['role_status'],
              "log_id" => $log->id
            );

            array_push($attendance_arr, $attnArr);
        }

        return $attendance_arr;

    }

    public function sentToHub($attendanceInLogFormat, $baseUrl, $token){

        $url = $baseUrl."api/attendance-mark-json";

        $data = array(
            "time" => Carbon::now()->format('d-m-Y  h:i:s'),
            "attendance" => $attendanceInLogFormat
        );

        try{
            $response = Http::withBody(json_encode($data), 'application/json')
                ->withHeaders([
                    'token' =>  $token,
                    'Content-Type' => 'application/json'
                ])
                ->post($url);

            // If response is not successful
            if($response->status() != 200){
                $res_arr = array(
                    "status" => "error",
                    "time" => Carbon::now()->format('d-m-Y  h:i:s'),
                    "log" => $response->status(),
                    "data" => ""
                );

                return $res_arr;
            }

            // Required parameters are mission in request
            if($response['status_code'] == 100499){
                $res_arr = array(
                    "status" => "error",
                    "time" => Carbon::now()->format('d-m-Y  h:i:s'),
                    "log" => $response->json(),
                    "data" => ""
                );

                return $res_arr;
            }

            // Get response successfully
            if($response['status_code'] == 100200){
                $res_arr = array(
                    "status" => "success",
                    "time" => Carbon::now()->format('d-m-Y h:i:s'),
                    "log" => "Data Fetched Successfully",
                    "data" => $response->json()
                );

                return $res_arr;

            }

            // Unknown Case
            $res_arr = array(
                "status" => "unknown",
                "time" => Carbon::now()->format('d-m-Y  h:i:s'),
                "log" => $response->json(),
                "data" => "Unknown"
            );

            return $res_arr;

        }catch(\Exception  $e) {
            // Return an error response
            $res_arr = array(
                "status" => "error",
                "time" => Carbon::now()->format('d-m-Y  h:i:s'),
                "log" => $e->getMessage(),
                "data" => ""
            );

            return $res_arr;
        }

    }

    public function setAttendanceSync($hubResponse, $appUser){

        $atArr = $hubResponse["data"]["data"]["attendance_marking"];


        foreach ($atArr as $at){
            if(isset($at["status_code"])){
                if($at["status_code"] == '100200'){
                    $arr = [
                        'sync' => 1,
                        'updated_by' => $appUser->id
                    ];
                    Attendance::where('id', '=', $at["attendance_id"])->update($arr);
                }
            }
        }
    }

    public function updateLog($data, $appUser){


        if(isset($data["data"]["data"]["attendance_marking"])){
            $atArr = $data["data"]["data"]["attendance_marking"];

            foreach ($atArr as $at){

                $statusCode = 2; //Unknown
                $syncStatus = 0;

                if($data['status'] == 'success'){
                    $statusCode = 1;
                    $syncStatus = 1;
                }

                if($data['status'] == 'error'){
                    $statusCode = 0;
                }

                $arr = [
                    'sync_status' => $syncStatus,
                    'status_code' => $statusCode,
                    'log' => json_encode($at),
                    'updated_by' => $appUser->id,
                ];
                SetAttendanceHistory::where('id', '=', $at["log_id"])->update($arr);
            }
        }else{
            $arr = [
                'uuid' => Str::uuid()->toString(),
                'employee_code' => "99999999999999",
                'created_by' => $appUser->id,
                'status_code' => 0,
                'log' => json_encode($data)
            ];
            SetAttendanceHistory::create($arr);
        }


        return true;
    }

}
