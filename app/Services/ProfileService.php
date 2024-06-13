<?php

namespace App\Services;

use App\Models\Manager\Config;
use App\Models\Manager\Employee;
use App\Models\Manager\GetEmployee;
use App\Models\Manager\GetEmployeeHistory;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProfileService
{
//235704
    public function handle(){

        //  Config for control iteration
        $configProfileAttempt = Config::where('key', 'config_profile_attempt')->first();
        $IsRunProfileAttempt = Config::where('key', 'make_profile_attempt')->first();
        $config = array(
            'config_profile_attempt' => $configProfileAttempt->value,
            'is_run_profile_attempt' => $IsRunProfileAttempt->value
        );

        $appUser = User::select('*')->where('email','=', 'app@app.com')->first();
        $baseUrl = \config('values.hub');
        $token = \config('values.token');


        // 1. Get Missing Profile Code
        $missingProfile = $this->getMissingProfile($config);



        if(!isset($missingProfile)){

            // Sync 2: Increase counter after completion of one iteration
            if($config["is_run_profile_attempt"] == 1){
                $confAttemptValueArr = [
                    'value' => $config["config_profile_attempt"] + 1
                ];
                $configProfileAttempt->update($confAttemptValueArr);

                // Sync 3: Control the flow sync. Stop increment on no data.
                $confMakeValueArr = [
                    'value' =>  0
                ];
                $IsRunProfileAttempt->update($confMakeValueArr);
            }


            return true;  // Not any missing profile exist from function
        }

        // Sync 3: Control the flow sync
        $confMakeValueArr = [
            'value' =>  1
        ];
        $IsRunProfileAttempt->update($confMakeValueArr);

        // Sync 1: Update Sync flag in profile after one attempt
        $arr = [
            'sync_iteration' => $config["config_profile_attempt"],
        ];
        $missingProfile->update($arr);

        // 2. Make Log
        $Log = $this->createLog($missingProfile, $appUser);

        // 3. Get detail from Hub
        $data = $this->getData($missingProfile, $baseUrl, $token);



        if(isset($data["data"]["data"])){
            if(isset($data["data"]["data"]["profile"])){
                    // 4. Make Entry in Profile/Employee Table
                    $createdProfile = $this->setProfile($data, $appUser);

                    // 5. Remove Entry from Missing Profile List/Get employees table
                    $deleted = $this->delMissingProfile($createdProfile);
            }
        }

        // 6. Update Log
        $this->updateLog($Log, $data, $appUser);

        return true;

    }

    public function getMissingProfile($config){

        $missingProfile = GetEmployee::select('*')
            ->where('sync_iteration', '<', $config["config_profile_attempt"])
            ->latest()
            ->first();

        return $missingProfile;
    }

    public function createLog($profile, $appUser){
        $arr = [
            'uuid' => Str::uuid()->toString(),
            'employee_code' => $profile['employee_code'],
            'created_by' => $appUser->id,
        ];
        $record = GetEmployeeHistory::create($arr);

        return $record;

    }

    public function getData($profile, $baseUrl, $token){
        $code = $profile->employee_code;
        $url = $baseUrl."api/profile/".$code;

        try{
            $response =  Http::withHeaders([
                'token' =>  $token,
                'Content-Type' => 'application/json'
            ])->get($url);

//            If response is not successful
            if($response->status() != 200){
                $res_arr = array(
                    "status" => "error",
                    "time" => Carbon::now()->format('d-m-Y  h:i:s'),
                    "log" => $response->status(),
                    "data" => ""
                );

                return $res_arr;
            }

//            Required parameters are mission in request
            if($response['status_code'] == 100499){
                $res_arr = array(
                    "status" => "error",
                    "time" => Carbon::now()->format('d-m-Y  h:i:s'),
                    "log" => $response->json(),
                     "data" => ""
                );

                return $res_arr;
            }

//          Get response successfully
            if($response['status_code'] == 100200){
                $res_arr = array(
                    "status" => "success",
                    "time" => Carbon::now()->format('d-m-Y h:i:s'),
                    "log" => "Data Fetched Successfully",
                    "data" => $response->json()
                );

                return $res_arr;

            }

//            Unknown Case
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

    public function updateLog($Log, $data, $appUser){

        if(isset($data["data"]["data"])){
            if(isset($data["data"]["data"]["profile"])){

                    $statusCode = 2; //Unknown

                    if($data['status'] == 'success'){
                        $statusCode = 1;
                    }

                    if($data['status'] == 'error'){
                        $statusCode = 0;
                    }

                    $arr = [
                        'sync_status' => 1,
                        'status_code' => $statusCode,
                        'log' => json_encode($data),
                        'updated_by' => $appUser->id,
                    ];

                    $Log->update($arr);

                    return true;
            }
        }

        $arr = [
            'uuid' => Str::uuid()->toString(),
            'employee_code' => "99999999999999",
            'created_by' => $appUser->id,
            'sync_status' => 0,
            'status_code' => 0,
            'log' => json_encode($data),
        ];

        GetEmployeeHistory::create($arr);

        return true;

    }

    public function setProfile($data, $appUser){
        $dt = $data["data"]["data"]; // data
        $pf = $data["data"]["data"]["profile"]; //profile
        $tt = $data["data"]["data"]["time"]; //time

        $imgURL = isset($dt['image']) ? $dt['image'] : null;


        // Save the image in local machine from image URL.
        if(isset($imgURL)){

            $imgStream = $imgURL;
            $name = basename($imgURL);
            $dirPath = 'front/coreui/assets/img/profile/';
            $imgURL = $dirPath . $dt['code'] . '.jpg';

            $image = file_get_contents($imgStream);
            File::put(public_path($imgURL), $image);
        }

        $arr = [
            'employee_code' => $dt['code'],
            'student_reg_no' => isset($pf['reg']) ? $pf['reg'] : null,
            'name' => $dt['name'],
            'image' => $imgURL,
            'campus' => isset($pf['campus_name']) ? $pf['campus_name'] : null,
            'designation' => isset($pf['desig_name']) ? $pf['desig_name'] : null,
            'department' => isset($pf['dept_name']) ? $pf['dept_name'] : null,
            'status' => isset($pf['status']) ? $pf['status'] : null,
            'role_status' => isset($dt['roleStatus']) ? $dt['roleStatus'] : null,

            'st_in_day1' => isset($tt['ST_INDAY1']) ? $tt['ST_INDAY1'] : null,
            'st_in_day2' => isset($tt['ST_INDAY2']) ? $tt['ST_INDAY2'] : null,
            'st_in_day3' => isset($tt['ST_INDAY3']) ? $tt['ST_INDAY3'] : null,
            'st_in_day4' => isset($tt['ST_INDAY4']) ? $tt['ST_INDAY4'] : null,
            'st_in_day5' => isset($tt['ST_INDAY5']) ? $tt['ST_INDAY5'] : null,
            'st_in_day6' => isset($tt['ST_INDAY6']) ? $tt['ST_INDAY6'] : null,
            'st_in_day7' => isset($tt['ST_INDAY7']) ? $tt['ST_INDAY7'] : null,

            'st_out_day1' => isset($tt['ST_OUTDAY1']) ? $tt['ST_OUTDAY1'] : null,
            'st_out_day2' => isset($tt['ST_OUTDAY2']) ? $tt['ST_OUTDAY2'] : null,
            'st_out_day3' => isset($tt['ST_OUTDAY3']) ? $tt['ST_OUTDAY3'] : null,
            'st_out_day4' => isset($tt['ST_OUTDAY4']) ? $tt['ST_OUTDAY4'] : null,
            'st_out_day5' => isset($tt['ST_OUTDAY5']) ? $tt['ST_OUTDAY5'] : null,
            'st_out_day6' => isset($tt['ST_OUTDAY6']) ? $tt['ST_OUTDAY6'] : null,
            'st_out_day7' => isset($tt['ST_OUTDAY7']) ? $tt['ST_OUTDAY7'] : null,

            'created_by' => $appUser->id,
        ];
        $record = Employee::updateOrCreate(
            ['employee_code' => $dt['code']],
            $arr);
        return $record;
    }

    public function delMissingProfile($profile){
        $isDeleted = GetEmployee::where('get_employees.employee_code','=', $profile['employee_code'])->delete();

        return $isDeleted;
    }

}
