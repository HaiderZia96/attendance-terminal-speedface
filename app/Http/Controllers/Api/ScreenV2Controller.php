<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manager\Attendance;
use App\Models\Manager\Employee;
use App\Models\Manager\Screen;
use App\Models\Manager\ScreenIp;
use App\Traits\Api\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Termwind\terminal;

class ScreenV2Controller extends Controller
{
    use Response;
    public function index(Request $request, $uuid )
    {

        // Get Ip against screen
        $screens = Screen::select('*')
            ->leftJoin('screen_ips', 'screen_ips.screen_id', '=', 'screens.id')
            ->where('screens.uuid', '=', $uuid)
            ->limit(6)
            ->get();

        $ipFilterArr = array();
        foreach ($screens as $screen){
            array_push($ipFilterArr , $screen->ip);
        }

        // Get Attendance from Postgresql
        $pgAttendances =  DB::connection('pgsql')
            ->table('last_two_months_attendance')
            ->whereIn('terminal_alias', $ipFilterArr)
            ->orderBy('last_two_months_attendance.id','desc')
            ->limit(10)
            ->get();

        $inOut = $this->calculateInOut($pgAttendances);


        // Get Profile Record
        $lastAttendance = Attendance::select('*')
            ->leftJoin('employees', 'employees.employee_code', '=', 'attendances.employee_code')
            ->where("attendances.employee_code", '=', isset($pgAttendances[0]->emp_code) ? $pgAttendances[0]->emp_code : '')
            ->orderBy('attendances.id', 'DESC')
            ->first();





        // 1: Set active card Data of screen
        $recordActive = array(
            "id" => isset($lastAttendance->id) ? $lastAttendance->id : null,
            "name" => isset($lastAttendance->name) ? $lastAttendance->name : null,
            "employee_code" => isset($pgAttendances[0]->emp_code) ? $pgAttendances[0]->emp_code : null,
            "punch_time" => isset($pgAttendances[0]->punch_time) ? $pgAttendances[0]->punch_time : null,
            "in_out" => $inOut,
            "student_reg_no" => isset($lastAttendance->student_reg_no) ? $lastAttendance->student_reg_no : null,
            "image" => isset($lastAttendance->image) ? $lastAttendance->image : null,
            "designation" => isset($lastAttendance->designation) ? $lastAttendance->designation : null,
            "department" => isset($lastAttendance->department) ? $lastAttendance->department : null,
            "campus" => isset($lastAttendance->campus) ? $lastAttendance->campus : null,
            "role_status" => isset($lastAttendance->role_status) ? $lastAttendance->role_status :null,
            "attendance_employee_code" => isset($pgAttendances[0]->emp_code) ? $pgAttendances[0]->emp_code  : null
        );



        // 2: Set the history record array.
        $startFromRecordNo = 1;
        $noOfRecordsInHistoryArray= 5;
        $recordHistory = array();

        for ($i = $startFromRecordNo; $i <= $noOfRecordsInHistoryArray; $i++){
            if(isset($pgAttendances[$i])){

                // Get Profile Record
                $lastAttendanceHistory = Attendance::select('*')
                    ->leftJoin('employees', 'employees.employee_code', '=', 'attendances.employee_code')
                    ->where("attendances.employee_code", '=', $pgAttendances[$i]->emp_code)
                    ->first();



                $inOutInvertHistory = 1;

                $recordActiveHistory = array(
                    "id" => isset($lastAttendanceHistory->id) ? $lastAttendanceHistory->id : null,
                    "name" => isset($lastAttendanceHistory->name) ? $lastAttendanceHistory->name : null,
                    "employee_code" => isset($pgAttendances[$i]->emp_code) ? $pgAttendances[$i]->emp_code : null,
                    "punch_time" => isset($pgAttendances[$i]->punch_time) ? $pgAttendances[$i]->punch_time : null,
                    "in_out" => $inOutInvertHistory,
                    "student_reg_no" => isset($lastAttendanceHistory->student_reg_no) ? $lastAttendanceHistory->student_reg_no : null,
                    "image" => isset($lastAttendanceHistory->image) ? $lastAttendanceHistory->image : null,
                    "designation" => isset($lastAttendanceHistory->designation) ? $lastAttendanceHistory->designation : null,
                    "department" => isset($lastAttendanceHistory->department) ? $lastAttendanceHistory->department : null,
                    "campus" => isset($lastAttendanceHistory->campus) ? $lastAttendanceHistory->campus : null,
                    "role_status" => isset($lastAttendanceHistory->role_status) ? $lastAttendanceHistory->role_status : null,
                    "attendance_employee_code" => isset($pgAttendances[$i]->emp_code) ? $pgAttendances[$i]->emp_code : null
                );

                array_push($recordHistory , $recordActiveHistory);
            }
        }




        $data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            "success" => ["Data Fetched Successfully"],
            "data" => [
                'active_profile_1'  => $recordActive,
                'profiles_history' => $recordHistory,
            ]
        ];

        $this->setResponse($data);
        return $this->getResponse();
    }

    public function calculateInOut($attendance){

        $screenIp = ScreenIp::select('*')
            ->where('ip', '=', isset($attendance[0]->terminal_alias) ? $attendance[0]->terminal_alias : '')
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

        // If on auto Calculate current In out state from PostgreSql
        $attnEmp = isset($attendance[0]->emp_code) ? $attendance[0]->emp_code : "";
        $query = "select *
                    from (
                    select *, row_number % 2 as m from
                    (
                        select *,
                        ROW_NUMBER () OVER (
                        ORDER BY id ASC
                        ) from last_two_months_attendance
                    WHERE Date(punch_time) = current_date
                    AND emp_code = '".$attnEmp."'
                    ) as ff
                    ) as ss  ORDER BY id DESC
                    ";

        $inOutHistory = DB::connection('pgsql')->select($query);

        $inOut = 1;
        if(isset($inOutHistory[0]->m)){
            if ($inOutHistory[0]->m == 0) {
                $inOut = 0;
            }
        }

        return $inOut;
    }
}
