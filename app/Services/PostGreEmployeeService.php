<?php
//
//namespace App\Services;
//
//use App\Models\Manager\Attendance;
//use App\Models\Manager\Config;
//use App\Models\Manager\Employee;
//use App\Models\Manager\GetEmployee;
//use App\Models\Manager\GetEmployeeHistory;
//use App\Models\Manager\PgGetAttendanceHistory;
//use App\Models\User;
//use Carbon\Carbon;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Http;
//use Illuminate\Support\Str;
//
//class PostGreEmployeeService
//{
//public function handle(){
//    $varSingleEmp = '';
//    $varGetEmp = '';
//    $varSetAttendance = '';
//    $varCreateGetEmployee = '';
//
//
//    // 1: Get data from post gre
//    $fetchSingleEmp = $this->getSingleEmployeeAttendance();
//    if(count($fetchSingleEmp) < 1){
//        return true;
//    }
//    $varSingleEmp = $fetchSingleEmp;
////    dd($varSingleEmp);
//
//    foreach ($varSingleEmp as $sEmp) {
//
//        // 2: Get employee from employee table
//        $getEmployee = $this->getEmployee($sEmp);
//        $varGetEmp = $getEmployee;
//
//
//        // 3: Mark Attendance
//        $setSingleEmp = $this->setEmployeeAttendance($sEmp);
//        $varSetAttendance = $setSingleEmp;
//
//
//        // 4: If employee not exist make entry in get employee
//        if ($varGetEmp->isEmpty()) {
//            $getEmp = $this->createEmployee($sEmp);
//            $varCreateGetEmployee = $getEmp;
//        }
//
//        // 5: Update/sync status in postgresql
//        if ($varCreateGetEmployee != null || "") {
//            $setAttSync = $this->setAttendanceSync($sEmp);
//        }
//    }
//}
//public function getSingleEmployeeAttendance(){
//
////       $att = new Attendance();
////       $att->setConnection('pgsql');
////       $att = DB::connection('pgsql')->table('iclock_transaction')->get();
//
//    $att =  DB::connection('pgsql')->table('last_two_months_attendance')
//        ->select('last_two_months_attendance.*', 'attendance_sync.id as attendance_sync_id', 'attendance_sync.last_two_months_attendance_id', 'attendance_sync.sync_at')
//        ->leftJoin('attendance_sync','last_two_months_attendance.id','=','attendance_sync.last_two_months_attendance_id')
//        ->whereNull('attendance_sync.sync_at')
//        ->orderBy('last_two_months_attendance.id','desc')
//        ->limit(20)
//        ->get();
////dd($att);
//
//    return $att;
////    $att = $att::select('*')->get();
//
//}
//
//public function getEmployee($singleAttendance){
////            dd($singleAttendance);
//        $emp = $singleAttendance->emp_code;
//        $employee_code = Employee::select('*')
//            ->where('employees.employee_code', '=', $emp)->get();
//
//            return $employee_code;
//}
//
//
//public function setEmployeeAttendance($empData){
//
//        $record = $this->employeeAttendance($empData);
//        if ($record) {
//            return $record;
//        } else {
//            info('Something Went Wrong');
//        }
//
//}
//
//
//    public function createEmployee($empData){
//        $record = $this->createGetEmployee($empData);
//        if ($record) {
//            return $record;
//        } else {
//            info('Something Went Wrong');
//        }
//
//    }
//
//    public function setAttendanceSync($singleAttendance){
//
////        DB::connection('pgsql')->table('attendance_sync')->insert();
////        dd($singleAttendance);
//        $record = DB::connection('pgsql')
//            ->table('attendance_sync')
//            ->insert([
//                'last_two_months_attendance_id' => $singleAttendance->id,
//                'sync_at' => now(),
//
//            ]);
//        return $record;
//
//    }
//
//        // For Store Data in Attendance
//    public function  employeeAttendance($singleAttendance){
////dd($singleAttendance);
//
//        $machineIP = \config('values.machine_ip');
//        $machineLoc = \config('values.machine_location');
//        $punch_format = Carbon::parse($singleAttendance->punch_time)->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');
//        $upload_format = Carbon::parse($singleAttendance->upload_time)->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');
//
////        $date =   date('Y-m-d H:i:s', strtotime($format));
////        $upload_format = date('Y-m-d H:i:sO', strtotime($singleAttendance[0]->upload_time));
//
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
////        dd($appUser[0]);
//        $arr = [
//
//            'punch_time' =>  $punch_format,
//            'area_alias' =>  $singleAttendance->area_alias,
//            'employee_id' =>$singleAttendance->emp_id,
//            'employee_code' =>  $singleAttendance->emp_code,
//            'punch_state' => $singleAttendance->punch_state,
//            'terminal_alias' => $singleAttendance->terminal_alias,
//            'terminal_sn'=> $singleAttendance->terminal_sn,
//            'upload_time' => $upload_format,
//            'machine_ip' => $machineIP,
//            'machine_location' => $machineLoc,
//            'created_by' => $appUser[0]->id,
//        ];
//
//
//        $record = Attendance::create($arr);
//        return $record;
//
//
//    }
//    public function  createGetEmployee($singleAttendance){
////        $machineIP = \config('values.machine_ip');
////        $machineLoc = \config('values.machine_location');
//        $appUser = User::select('*')->where('email','=', 'app@app.com')->get();
//        $arr = [
//            'employee_code' =>  $singleAttendance->emp_code,
//            'created_by' => $appUser[0]->id,
//        ];
//        $record = GetEmployee::create($arr);
//        return $record;
//    }
//}
