<?php

namespace App\Services;

use App\Models\Manager\Attendance;
use App\Models\Manager\Employee;
use App\Models\Manager\GetEmployee;
use App\Models\Manager\ScreenIp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostGreAttendanceSync
{
    public function handle(){

        // Get attendance from PostGre
        $attendances = $this->getAttendancePGSQL();
        if(count($attendances) < 1){
            return true;
        }

        foreach ($attendances as $attendance) {

            $record = DB::connection('pgsql')
            ->table('attendance_sync')
            ->insert([
                'last_two_months_attendance_id' => $attendance->id,
                'in_process' => 1
            ]);



            // Get Profile
            $profile = $this->getProfile($attendance->emp_code);


            $inOut = $this->calculateInOut($attendance);


            // Mark Attendance
            $attnMarked = $this->setAttendance($attendance, $inOut);
            if($attnMarked){
                $this->setSyncPGSQL($attendance->id);
            }
            // Mark profile missing
            if(!isset($profile)){
                $this->markProfileMissing($attendance->emp_code);
            }

        }

        return true;

    }

    public function getAttendancePGSQL(){
        $attendance_arr =  DB::connection('pgsql')->table('last_two_months_attendance')
            ->select('last_two_months_attendance.*', 'attendance_sync.id as attendance_sync_id', 'attendance_sync.last_two_months_attendance_id', 'attendance_sync.sync_at')
            ->leftJoin('attendance_sync','last_two_months_attendance.id','=','attendance_sync.last_two_months_attendance_id')
            ->whereNull('attendance_sync.sync_at')
            ->whereNull('attendance_sync.in_process')
            ->orderBy('last_two_months_attendance.id','desc')
            ->limit(10)
            ->get();

        return $attendance_arr;
    }

    public function getProfile($ProfileCode){

        $profile = Employee::select('*')
            ->where('employees.employee_code', '=', $ProfileCode)->first();

        return $profile;
    }

    public function calculateInOut($attendance){

        $screenIp = ScreenIp::select('*')
            ->where('ip', '=', $attendance->terminal_alias)
            ->first();

        $inOutType = isset($screenIp->type) ? $screenIp->type : 2;

        if($inOutType == 1){
            $inOut = 1;
            return $inOut;
        }

        if($inOutType == 0){
            $inOut = 0;
            return $inOut;
        }


        // If on auto
            $inOut = 1;
            $todayAttendance = $this->getLatestAttendance($attendance->emp_code);
            if(isset($todayAttendance->punch_time)){
                $todayAttendanceDate = Carbon::parse($todayAttendance->punch_time);
                if($todayAttendanceDate->isToday()){
                    if(isset($todayAttendance->in_out)){
                        if($todayAttendance->in_out == 1){
                            $inOut = 0;
                        }
                    }
                }
            }

            return $inOut;
    }

    public function markProfileMissing($ProfileCode){
        $appUser = User::select('*')->where('email','=', 'app@app.com')->first();

        $arr = [
            'employee_code' =>  $ProfileCode,
            'created_by' => $appUser->id,
        ];

        $record = GetEmployee::updateOrCreate(
            [
                'employee_code' =>  $ProfileCode
            ]
            ,$arr);
        return $record;
    }


    public function setAttendance($attendance, $inOut){

        $appUser = User::select('*')->where('email','=', 'app@app.com')->first();
        $machineIP = \config('values.machine_ip');
        $machineLoc = \config('values.machine_location');
        $punch_format = Carbon::parse($attendance->punch_time)->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');
        $upload_format = Carbon::parse($attendance->upload_time)->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');

        $arr = [

            'punch_time' =>  $punch_format,
            'area_alias' =>  $attendance->area_alias,
            'employee_id' =>$attendance->emp_id,
            'employee_code' =>  $attendance->emp_code,
            'punch_state' => $attendance->punch_state,
            'terminal_alias' => $attendance->terminal_alias,
            'terminal_sn'=> $attendance->terminal_sn,
            'upload_time' => $upload_format,
            'machine_ip' => $machineIP,
            'machine_location' => $machineLoc,
            'in_out' => $inOut,
            'created_by' => $appUser->id,
        ];

        $record = Attendance::create($arr);
        return $record;

    }

    public function setSyncPGSQL($attendanceId){
        $record = DB::connection('pgsql')
            ->table('attendance_sync')
            ->where('last_two_months_attendance_id', '=', $attendanceId)
            ->update([
                'sync_at' => now()
            ]);
        return $record;
    }


    public function getLatestAttendance($ProfileCode){

        $attn = Attendance::select('*')
            ->where('employee_code','=', $ProfileCode)
            ->latest('id')->first();
        return $attn;

    }


}
