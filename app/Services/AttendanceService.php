<?php
//
//namespace App\Services;
//
//
//use App\Models\Manager\Attendance;
//use App\Models\Manager\Config;
//use App\Models\Manager\SetAttendanceHistory;
//use App\Models\User;
//use Carbon\Carbon;
//use Exception;
//use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Str;
//
//class AttendanceService
//{
//
//    public function handle(){
//        $varGetAtt = '';
//        $varCreateLog = '';
//        $varSetErp = '';
//        $varUpdateLog = '';
//        $varFailed = '';
//
//        //  get Config_att_attempt value
//        $configAttempt = Config::where('key', 'config_att_attempt')->first();
//        $makeAttempt = Config::where('key', 'make_att_attempt')->first();
//
//        // 1: Get attendance
//        $getAtt = $this->getAttendance();
//        $varGetAtt = $getAtt;
//
//
//        //   if ($varGetAtt == null || ""){
//        //       return back();
//        //   }
//
//        // 2: make log
//        if($varGetAtt != null || ''){
//            $clog = $this->createAttendanceLog($varGetAtt);
//            $varCreateLog = $clog;
//        }
//
//        // 3: send to hub
//        if($varCreateLog != null || ""){
//            $setA =$this->setAttendance($varGetAtt);
//            $varSetErp = $setA;
//        }
//
//
//        //  get last record where sync_iteration < config_attempt and sync != 1
//        if(isset($varGetAtt)){
//            //  if found make_attempt on config is 1
//            $configArr = [
//                'value' => 1,
//            ];
//            $makeAttempt->update($configArr);
//            //  set sync_iteration value equal to config_att_attempt value
//            $attendanceArr = [
//                'sync_iteration' => $configAttempt->value,
//            ];
//            $varGetAtt->update($attendanceArr);
//
//
//
//            // sync status is update
//
//                if($varSetErp->status() == 200  && $varSetErp->getData()->status_code == 100200) {
//                    $synarr = [
//                        'sync' => 1,
//                        'mark_time' => Carbon::now(),
//                    ];
//                    $varGetAtt->update($synarr);
//                }
//                else if ($varSetErp->status() == 200  && $varSetErp->getData()->status_code == 100499){
//
//                    // 4: failed response (Fields Error)
//                    $setEmp = $this->failedResponse($varSetErp);
//                    $varFailed = $setEmp;
//                }
//                else{
//
//                    // 4: failed response (Server Error)
//                    $setEmp = $this->failedResponse($varSetErp);
//                    $varFailed = $setEmp;
//                }
//
//        }
//        else{
//            //  increment on value of config_att_attempt where make_attempt equal to 1
//            $configMakeAttempt = Config::where('key', 'make_att_attempt')->where('value', 1)->first();
//            if(isset($configMakeAttempt)){
//                //  increment value of config_att_attempt
//                $configAttempt->value = $configAttempt->value + 1;
//                $configAttempt->save();
//                //  make_att_attempt is 0
//                $makeAttemptArr = [
//                    'value' => 0,
//                ];
//                $configMakeAttempt->update($makeAttemptArr);
//            }
//        }
//
//
//        // 5: update log
//        $ulog =  $this->updateAttendanceLog($varCreateLog,$varSetErp);
//
//
//    }
//    public function getAttendance(){
//        $configAttempt = Config::where('key', 'config_att_attempt')->first();
//        $getAttendance = Attendance::select('attendances.*','employees.role_status as role_status')
//            ->leftJoin('employees', 'employees.employee_code', '=', 'attendances.employee_code')
//            ->where('sync_iteration', '<', $configAttempt->value)->where('sync', '!=', 1)
//            ->latest('attendances.created_at')->first();
//        if($getAttendance != null || "") {
//            return $getAttendance;
//        }
//    }
//
//    public function createAttendanceLog($att){
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
//        if($att != null || '') {
//            $arr = [
//                'uuid' => Str::uuid()->toString(),
//                'employee_code' => $att['employee_code'],
//                'created_by' => $appUser[0]->id,
//            ];
//            $record = SetAttendanceHistory::create($arr);
//            return $record;
//
//        }
//    }
//
//    public function setAttendance($empAttData){
//
//        $baseUrl = \config('values.hub');
//        $machineIP = \config('values.machine_ip');
//        $token = \config('values.token');
//
//
//
//        try {
//            // Perform API request using Laravel's HTTP client or any other API client library
//            $response = Http::withHeaders( [
//                    'token' =>  $token,
//                    'Content-Type' => 'application/json',
//                ]
//            )->post($baseUrl.'api/attendance-mark', [
//                'employee_code' => $empAttData['employee_code'],
//                'punch_time' =>$empAttData['punch_time'],
//                'terminal_ip'=> $machineIP,
//                'sub_terminal_ip'=> $empAttData['terminal_alias'],
//                'role_status' => $empAttData['role_status'],
//                'in_out'=> $empAttData['in_out'],
//            ]);
//
//
//            // Check for successful API response
//            if ($response['status_code'] == 100200) {
//
//                // Process the successful response
//                $attendanceDetails = $response->json();
//                $success =  response()->json([
//                    'message' => $attendanceDetails['noti']['success'][0],
//                    'data' => $attendanceDetails['data'],
//                    'status_code' => $attendanceDetails['status_code']
//                ]);
//
//                return $success;
//
//            } else {
//
//                $attendanceDetails = $response->json();
//                $error = response()->json([
//                    'message' => $attendanceDetails['noti']['info'][0],
//                    'data' =>  $attendanceDetails['noti']['info'][0],
//                    'status_code' => $attendanceDetails['status_code']
//                ]);
//
//                return $error;
//
//            }
//        } catch (Exception $ex) {
//
//            Log::error($ex->getMessage());
//
//            // Return an error response
//            return response()->json(['error' => 'An error occurred'], $response->status());
//        }
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
//
//        }else{
//            $failedRes = response()->json([
//                'message' => $setRep->statusText(),
//                'data' =>  [],
//            ],$setRep->status());
//            return $failedRes;
//        }
//    }
//
//    public function updateAttendanceLog($attendanceDetails, $res){
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
//        if($attendanceDetails == null || "" ) {
//
//        }else {
//
//            $record =
//                SetAttendanceHistory::select('*')->
//                where('uuid',$attendanceDetails['uuid'])
//                    ->get();
//
//                if($res->status() == 200 && $res->getData()->status_code == 100200){
//
//                    $arr = [
//                        'sync_status' => 1,
//                        'status_code' => 1,
//                        'log' => $res->getData()->message,
//                        'updated_by' => $appUser[0]->id,
//                    ];
//                    $update = $record[0]->update($arr);
//
//                    return $update;
//                }
//
//            else if($res->status() == 200 && $res->getData()->status_code == 100499) {
//
//                    $arr = [
//                        'status_code' => 0,
//                        'log' => $res->getData()->message,
//                        'updated_by' => $appUser[0]->id,
//
//                    ];
//                    $update = $record[0]->update($arr);
//                    return $update;
//                }
//            else {
//
//                $arr = [
//                    'status_code' => 2,
//                    'log' => $res->statusText(),
//                    'updated_by' => $appUser[0]->id,
//
//                ];
//                $update = $record[0]->update($arr);
//                return $update;
//            }
//        }
//    }
//}
//
