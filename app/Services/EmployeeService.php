<?php
//
//namespace App\Services;
//
//use App\Models\Manager\Config;
//use App\Models\Manager\Employee;
//use App\Models\Manager\GetEmployee;
//use App\Models\Manager\GetEmployeeHistory;
//use App\Models\User;
//use Carbon\Carbon;
//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Str;
//use Throwable;
//
//
//class EmployeeService
//{
//    public function handle(){
//        $varGetEmp = '';
//        $varClog = '';
//        $varHubDetail = '';
//        $varSetEmp = '';
//        $varDeleteGetEmp = '';
//        $varUlog = '';
//        $varFailed = '';
//
//        //  get Config_attempt value
//        $configAttempt = Config::where('key', 'config_emp_attempt')->first();
//        $makeAttempt = Config::where('key', 'make_emp_attempt')->first();
//
//
//        // 1: get employee entry
//        $getEmp = $this->getEmployee();
//        if($getEmp == null || ""){
//            return true;
//        }
//        $varGetEmp = $getEmp;
//
//        // 2: create log
//        $clog = $this->createLog($varGetEmp);
//        $varClog = $clog;
//
//
//        // 3: get employee details from hub
//        if ($varClog != null || "") {
//            $employeeDetails =  $this->getData($varClog);
//            $varHubDetail = $employeeDetails;
//
//        }
//
//        // 4: make entry in employee table
////        if($varHubDetail != null || '') {
//            if ($varHubDetail->status() == 200) {
////dd($varHubDetail->getData()->data);
//                if ($varHubDetail->getData()->status_code == 100200 && $varHubDetail->getData()->data != null || '') {
//                    $setEmp = $this->setEmployee($varHubDetail);
//                    $varSetEmp = $setEmp;
//                }
//            }
//
////        }
//
//
//        //  Get last record where sync_iteration < config_emp_attempt and sync != 1
//        if(isset($varGetEmp)){
//            //  if found make_emp_attempt on config is 1
//            $configArr = [
//                'value' => 1,
//            ];
//            $makeAttempt->update($configArr);
//            //  set sync_iteration value equal to config_emp_attempt value
//            $employeeArr = [
//                'sync_iteration' => $configAttempt->value,
//            ];
//            $varGetEmp->update($employeeArr);
//
//            //  Get Employee sync status is update
//            if($varHubDetail->status() == 200) {
//                If($varHubDetail->getData()->status_code == 100200 &&  $varHubDetail->getData()->data != null || ''){
//                    $synarr = [
//                    'sync' => 1,
//                    'mark_time' => Carbon::now(),
//                ];
//                $varGetEmp->update($synarr);}
//            }else{
//                $setEmp = $this->failedResponse($varHubDetail);
//                $varFailed = $setEmp;
//
//            }
//        }else{
//
//            //  increment on value of config_emp_attempt where make_emp_attempt equal to 1
//            $configMakeAttempt = Config::where('key', 'make_emp_attempt')->where('value', 1)->first();
//
//            if(isset($configMakeAttempt)){
//                //  increment value of config_emp_attempt
//                $configAttempt->value = $configAttempt->value + 1;
//                $configAttempt->save();
//                //  make_emp_attempt is 0
//                $makeAttemptArr = [
//                    'value' => 0,
//                ];
//                $configMakeAttempt->update($makeAttemptArr);
//            }
//        }
//
////        if($varHubDetail->status() != 200){
////            $setEmp = $this->failedResponse($varHubDetail);
////            $varFailed = $setEmp;
////        }
//
//        // 5: remove entry from get employee
//        if($varSetEmp != "" || null){
//            $deleteEmp =     $this->deleteGetEmployee($varGetEmp);
//            $varDeleteGetEmp = $deleteEmp;
//
//        }
//        // 5: update log
//        $employeeDetails =  $this->updateLog($varClog,$varHubDetail);
//        $varUlog = $employeeDetails;
//
//    }
//    public function getEmployee(){
//        $configAttempt = Config::where('key', 'config_emp_attempt')->first();
////        $getEmp = GetEmployee::select('*')->orderBy('id', 'desc')->first();
//        $getEmp = GetEmployee::where('sync_iteration', '<', $configAttempt->value)->where('sync', '!=', 1)->latest()->first();
//
//        if($getEmp != null || "") {
//            $record = GetEmployee::find($getEmp['id']);
//            return $record;
//        }
//
//    }
//    public function  createLog($fetchEmp){
////        $getEmp = GetEmployee::select('*')->orderBy('id', 'desc')->first();
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
//        if($fetchEmp != null || "") {
//            $arr = [
//                'uuid' => Str::uuid()->toString(),
//                'employee_code' => $fetchEmp['employee_code'],
//                'created_by' => $appUser[0]->id,
//            ];
//            $record = GetEmployeeHistory::create($arr);
//
//            return $record;
//        }
//    }
//
//    public function getData($getEmp){
//
//        $empid = $getEmp['employee_code'];
//        $baseUrl = \config('values.hub');
//        $token = \config('values.token');
//        $hubBaseUrl = $baseUrl."api/profile/".$empid;
//
//
//        try {
//            $response =  Http::withHeaders([
//                'token' =>  $token,
//                'Content-Type' => 'application/json'
//            ])->get($hubBaseUrl);
//            $employeeDetails = $response->json();
//
//
//                if($response->status() == 200 && $response['status_code'] == 100200) {
//
//                    $success = response()->json([
//                        'message' => $employeeDetails['noti']['success'][0],
//                        'data' => $employeeDetails['data'],
//                        'status_code' => $employeeDetails['status_code']
//                    ]);
////                    dd($success);
//
//                    return $success;
//                }
//
//           else if($response->status() == 200 && $response['status_code'] == 100499){
//
//
//                $error = response()->json([
//                    'message' => $employeeDetails['noti']['error'][0],
//                    'data' =>  $employeeDetails['noti']['info'],
//                    'status_code' => $employeeDetails['status_code']
//                ]);
//                return $error;
//            }
//            else{
//                // Return an error response
//                return response()->json(['error' => 'An error occurred'], $response->status());
//
//            }
//
//        }
//        catch (Throwable  $ex) {
//             // Log the exception
//            Log::error($ex->getMessage());
//
//            // Return an error response
//            return response()->json(['error' => 'An error occurred'], $response->status());
//        }
//
//
//
//    }
//
//    public function failedResponse($setRep){
//
//        if($setRep->status() == 200){
//            $failedRes = response()->json([
//                'message' => $setRep->getData()->message,
//                'data' =>  $setRep->getData()->data
//            ],$setRep->status());
//            return $failedRes;
//        }else{
//            $failedRes = response()->json([
//                'message' => $setRep->statusText(),
//                'data' =>  [],
//            ],$setRep->status());
//
//            return $failedRes;
//        }
//    }
//
//    public function setEmployee($employeeDetails){
//
//        $data = $employeeDetails->getData()->data->profile;
//        $roleStatus = $employeeDetails->getData()->data->roleStatus;
//        $att_time = $employeeDetails->getData()->data->startTime;
//        $imageData = $employeeDetails->getData()->data;
//
//
//
//        if(isset($data->EMP_ID)){
//            $emp = Employee::select('*')
//                ->where('employees.employee_code','=',$data->EMP_ID)
//                ->orderBy('id', 'desc')->count();
//        }else{
//            $emp = Employee::select('*')
//                ->where('employees.employee_code','=',$data->STUDENT_ID)
//                ->orderBy('id', 'desc')->count();
//        }
//
//
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
//
////        Profile Image Retrieve
//        $url = $imageData->image;
//        $name = substr($url, strrpos($url, '/') + 1).'.jpg';
//        $folderPath = 'front/coreui/assets/img/profile/';
//        $filename = $name;
//        $file = $folderPath . $filename;
////        dd($file);
//
//        $image = file_get_contents($url);
//        $path = File::put(public_path($file), $image);
//        if(isset($path)){
//            $resImg = url($file);
//        }
//
//        if ($emp > 0){
//            return true;
//        }
//
//        else if(isset($data->EMP_ID)){
//            $employee_code = $data->EMP_ID;
//            $arr = [
//                'employee_code' => $employee_code,
//                'name' => $data->NAME,
//                'image' => $file,
//                'campus' => $data->CAMPUS_NAME,
//                'designation' => $data->DESIG_NAME,
//                'department' => $data->DEPT_NAME,
//                'status' => $data->STATUS,
//                'role_status' => $roleStatus,
//
//                'st_in_day1' => $att_time->ST_INDAY1,
//                'st_in_day2' => $att_time->ST_INDAY2,
//                'st_in_day3' => $att_time->ST_INDAY3,
//                'st_in_day4' => $att_time->ST_INDAY4,
//                'st_in_day5' => $att_time->ST_INDAY5,
//                'st_in_day6' => $att_time->ST_INDAY6,
//                'st_in_day7' => $att_time->ST_INDAY7,
//
//                'st_out_day1' => $att_time->ST_OUTDAY1,
//                'st_out_day2' => $att_time->ST_OUTDAY2,
//                'st_out_day3' => $att_time->ST_OUTDAY3,
//                'st_out_day4' => $att_time->ST_OUTDAY4,
//                'st_out_day5' => $att_time->ST_OUTDAY5,
//                'st_out_day6' => $att_time->ST_OUTDAY6,
//                'st_out_day7' => $att_time->ST_OUTDAY7,
//
//                'created_by' => $appUser[0]->id,
//            ];
//            $record = Employee::create($arr);
//            return $record;
//
//
//
//        }else{
//            $employee_code = $data->STUDENT_ID;
//            $arr = [
//                'employee_code' => $employee_code,
//                'name' => $data->NAME,
//                'image' => $resImg,
//                'campus' => $data->CAMPUS_NAME,
//                'role_status' => $roleStatus,
//                "student_reg_no" => $data->REG,
//                'created_by' => $appUser[0]->id,
//            ];
//            $record = Employee::create($arr);
//            return $record;
//        }
//
//    }
//
//
//
//    public function deleteGetEmployee( $empRecord){
//
//        $emp = GetEmployee::select('*')->where('get_employees.employee_code','=', $empRecord['employee_code'])->get();
//
//        if (empty($emp)) {
//            abort(404, 'NOT FOUND');
//        }
//        else{
//            foreach ($emp as $e){
//                $status = $e->delete();
//            }
//            return $status;
//
//        }
//    }
//
//    public function updateLog($employeeDetails,$varHubDetail){
//
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
//        if($employeeDetails == null || "" ) {
//
//        }else {
//
////            $getEmp = GetEmployeeHistory::select('*')
////                ->where('sync_status', 0)
////                ->orderBy('id', 'desc')->first();
////
//            $record =
//                GetEmployeeHistory::select('*')->
//                where('uuid',$employeeDetails['uuid'])
//                    ->get();
//
//
//            if($varHubDetail->status() == 200 && $varHubDetail->getData()->status_code == 100200){
//                $arr = [
//                    'sync_status' => 1,
//                    'status_code' => 1,
//                    'log' => $varHubDetail->getData()->message,
//                    'updated_by' => $appUser[0]->id,
//                ];
//                $update = $record[0]->update($arr);
//                return $update;
//
//            }
//                if($varHubDetail->status() == 200 && $varHubDetail->getData()->status_code == 100499){
//                    $arr = [
//                        'status_code' => 0,
//                        'log' => $varHubDetail->getData()->message,
//                        'updated_by' => $appUser[0]->id,
//
//                    ];
//                    $update = $record[0]->update($arr);
//                    return $update;
//
//                }
//            else {
//                $arr = [
//                    'status_code' => 2,
//                    'log' => $varHubDetail->statusText(),
//                    'updated_by' => $appUser[0]->id,
//
//                ];
//                $update = $record[0]->update($arr);
//                return $update;
//            }
//
//        }
//    }
//}
