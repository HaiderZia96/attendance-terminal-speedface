<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\Attendance;
use App\Models\Manager\Employee;
use App\Models\Manager\GetEmployee;
use App\Services\AttendanceService;
use App\Services\AttendanceServiceV2;
use App\Services\PostGreAttendanceSync;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Models\Activity;

class AttendanceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_attendance_attendance-list', ['only' => ['index','getIndex']]);
        $this->middleware('permission:manager_attendance_attendance-create', ['only' => ['create','store']]);
        $this->middleware('permission:manager_attendance_attendance-show', ['only' => ['show']]);
        $this->middleware('permission:manager_attendance_attendance-pg-attendance-sync', ['only' => ['test']]);
        $this->middleware('permission:manager_attendance_attendance-erp-attendance-sync', ['only' => ['testErp']]);
        $this->middleware('permission:manager_attendance_attendance-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:manager_attendance_attendance-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_attendance_attendance-delete-all', ['only' => ['deleteAll']]);
        $this->middleware('permission:manager_attendance_attendance-activity-log', ['only' => ['getActivity','getActivityLog','getTrashActivity','getTrashActivityLog']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'page_title' => 'Attendance',
            'p_title' => 'Attendance',
            's_title' => 'Attendance',
            'p_summary' => 'List of Attendance',
            'p_description' => null,
            'url' => route('manager.attendance.create'),
            'url_text' => 'Add New',
            'pg_url' => route('manager.attendance.test'),
            'pg_url_text' => 'PG Attendance Sync',
            'erp_url' => route('manager.attendance.erp.test'),
            'erp_url_text' => 'Erp Attendance Sync',
            'trash' => route('manager.get.attendance-activity-trash'),
            'trash_text' => 'View Trash',
            'delete_all' => route('manager.attendance-delete-all'),
            'delete_all_text' => 'Delete All',
        ];
//        dd($departments);
        return view('manager.attendance.index')->with($data);
    }

    public function getIndex(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $where = [];
        //Sync Status
        if (!is_null($request->get('sync_status'))) {
            $sync_status = $request->get('sync_status');
            $var = ['attendances.sync', 'like', '%' . $sync_status . '%'];
            array_push($where, $var);
        }

        if (!empty($request->get('start_date'))) {
            $var = ['attendances.punch_time', '>=', $request->get('start_date') . ' 00:00:00'];
            array_push($where, $var);
        }
        if (!empty($request->get('end_date'))) {
            $var = ['attendances.punch_time', '<=', $request->get('end_date') . ' 23:59:59'];
            array_push($where, $var);
        }



        $totalRecords = Attendance::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'attendances.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('attendances.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records with filter
        $totalRecordswithFilter = Attendance::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'attendances.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('attendances.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records
        $records = Attendance::orderBy($columnName, $columnSortOrder)
            ->leftJoin('users', 'users.id', '=', 'attendances.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('attendances.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->select('attendances.*')
            ->orderBy('id', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//         dd($records);
        $data_arr = array();

        foreach ($records as $record) {

         $date =   date('Y-m-d H:i:s', strtotime($record->created_at));


            $id = $record->id;
            $punch_time = $record->punch_time;
            $upload_time = $record->upload_time;
//            if(isset($record['TerminalID']['serial_number'])){
//                $terminal_sn = $record['TerminalID']['serial_number'];
//            }
//            else{
//                $terminal_sn = "";
//            }
            $terminal_sn   = $record->terminal_sn;
            $terminal_alias   = $record->terminal_alias;
            $employee_id = $record->employee_id;
//            if(isset($record['EmpID']['emp_id'])){
//                $employee_code = $record['EmpID']['emp_id'];
//            }
//            else{
//                $employee_code = "";
//            }
            $employee_code = $record->employee_code;
            $area_alias =$record->area_alias;
            $sync =$record->sync;

            $created_at =$date;

            $machine_ip =$record->machine_ip;
            $machine_location =$record->machine_location;
            $punch_state =$record->punch_state;
            $is_mask =$record->is_mask;
            $mark_time =$record->mark_time;
            $in_out =$record->in_out;
            $sync_iteration =$record->sync_iteration;

//            $punch = Carbon::parse($upload_time . '  Asia/Karachi')->tz('UTC');
//                $format = Carbon::createFromFormat('Y-m-d H:i:sO', $upload_time, 'UTC')
//                    ->setTimezone('Asia/Karachi');
//            dd($format);
//            $punch =  date('Y-m-d H:i:s', strtotime($upload_time));
//           dd($punch);
//                $date =   date('Y-m-d H:i:s', strtotime($format)),
//                date('Y-m-d H:m:s', strtotime($date)),
            $data_arr[] = array(
                "id" => $id,
                "punch_time" => $punch_time,
                "terminal_sn" => $terminal_sn,
                "employee_code" => $employee_code,
                "employee_id" => $employee_id,
                "terminal_alias" => $terminal_alias,
                "upload_time" => $upload_time,
                "area_alias" => $area_alias ,
                "sync" => $sync ,
                "created_at"=>$created_at,
                "machine_ip" => $machine_ip,
                "machine_location" => $machine_location,
                "punch_state" => $punch_state,
                "is_mask" => $is_mask,
                "mark_time" => $mark_time,
                "in_out" => $in_out,
                "sync_iteration" => $sync_iteration,

            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'page_title' => 'Attendance',
            'p_title' => 'Attendance',
            'p_summary' => 'Add Attendance',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.attendance.store'),
            'url' => route('manager.attendance.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
        return view('manager.attendance.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'punch_time' => 'required',
            'employee_code' => 'required',
            'upload_time' => 'required',
            'in_out' => 'required'
        ]);

        $emp = $request->input('employee_code');
        $employee_code = Employee::select('employees.employee_code as emp_code')
            ->where('employees.employee_code', '=' , $emp )->get();

     if ($employee_code->isEmpty()){

         $record =  $this->notHaveEmployee($request);
         if ($record) {
             $messages = [
                 array(
                     'message' => 'Record created successfully',
                     'message_type' => 'success'
                 ),
             ];
             Session::flash('messages', $messages);

             return redirect()->route('manager.attendance.index');
         } else {
             abort(404, 'NOT FOUND');
         }
     }
     else{

       $record =  $this->haveEmployee($request);

         if ($record) {
             $messages = [
                 array(
                     'message' => 'Record created successfully',
                     'message_type' => 'success'
                 ),
             ];
             Session::flash('messages', $messages);

             return redirect()->route('manager.attendance.index');
         } else {
             abort(404, 'NOT FOUND');
         }
     }



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record= Attendance::select('attendances.*')
            ->where('attendances.id', '=' ,$id )
            ->first();
//        dd($record);
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }

        // Add activity logs
        $user = Auth::user();
        activity('Attendance')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name'=>$record->employee_code]])
            ->log('viewed');
        $data = array(
            'page_title'=>'Attendance',
            'p_title'=>'Attendance',
            'p_summary'=>'Show Attendance',
            'p_description'=>null,
            'method' => 'POST',
            'action' => route('manager.attendance.update',$record->id),
            'url'=>route('manager.attendance.index'),
            'url_text'=>'View All',
            'data'=>$record,
            'enctype' => 'application/x-www-form-urlencoded',
        );
//        dd($data);
        return view('manager.attendance.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record= Attendance::select('attendances.*')
            ->where('attendances.id', '=' ,$id )
            ->first();
//        dd($record);
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }

        $data = array(
            'page_title' => 'Attendance',
            'p_title' => 'Attendance',
            'p_summary' => 'Edit Attendance',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.attendance.update', $record->id),
            'url' => route('manager.attendance.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data',
        );
//dd($data);
        return view('manager.attendance.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Attendance::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $this->validate($request, [
            'punch_time' => 'required',
            'employee_code' => 'required',
            'upload_time' => 'required',
            'in_out' => 'required'
        ]);



        $machineIP = \config('values.machine_ip');
        $machineLoc = \config('values.machine_location');
//        $format = Carbon::createFromFormat('Y-m-d H:i:sO', $singleAttendance[0]->punch_time, 'UTC')
//                ->setTimezone('Asia/Karachi'),
//
//            $date =   date('Y-m-d H:i:s', strtotime($format)),
        $arr = [
            'punch_time' => $request->input('punch_time'),
            'upload_time' => $request->input('upload_time'),
            'area_alias' =>  $request->input('area_alias'),
            'employee_id' => $request->input('employee_id'),
            'employee_code' => $request->input('employee_code'),
            'in_out' => $request->input('in_out'),
            'terminal_alias' => $request->input('terminal_alias'),
            'terminal_sn'=>$request->input('terminal_sn'),
            'machine_ip' => $machineIP,
            'machine_location' => $machineLoc,
            'updated_by' => Auth::user()->id,
        ];
        $record->update($arr);
        $messages = [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('manager.attendance.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Attendance::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $record->delete();
        $messages = [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('manager.attendance.index');
    }


    public function getAttendanceEmpIndexSelect(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Employee::select('employees.id as id', 'employees.name as name' , 'employees.employee_code as employee_code')
                ->where(function ($q) use ($search) {
                    $q->where('employees.employee_code', 'like', '%' . $search . '%');
                })
                ->get();
        }

        return response()->json($data);

    }
    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Attendance Activity',
            'p_title' => 'Attendance Activity',
            'p_summary' => 'Show Attendance Activity',
            'p_description' => null,
            'url' => route('manager.attendance.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.attendance.activity')->with($data);
    }

    public function getActivityLog(Request $request, string $id)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Attendance::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Attendance::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Attendance::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $attributes = (!empty($record->properties['attributes']) ? $record->properties['attributes'] : '');
            $old = (!empty($record->properties['old']) ? $record->properties['old'] : '');
            $current = '<ul class="list-unstyled">';
            //Current
            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    if (is_array($value)) {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    } else {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                }
            }
            $current .= '</ul>';
            //Old
            $oldValue = '<ul class="list-unstyled">';
            if (!empty($old)) {
                foreach ($old as $key => $value) {
                    if (is_array($value)) {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    } else {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                }
            }
            //updated at
            $updated = 'Updated:' . $record->updated_at->diffForHumans() . '<br> At:' . $record->updated_at->isoFormat('llll');
            $oldValue .= '</ul>';
            //Causer
            $causer = isset($record->causer) ? $record->causer : '';
            $type = $record->description;
            $data_arr[] = array(
                "id" => $id,
                "current" => $current,
                "old" => $oldValue,
                "updated" => $updated,
                "causer" => $causer,
                "type" => $type,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function getTrashActivity()
    {
        //Data Array
        $data = array(
            'page_title' => 'Attendance Activity',
            'p_title' => 'Attendance Activity',
            'p_summary' => 'Show Attendance Trashed Activity',
            'p_description' => null,
            'url' => route('manager.attendance.index'),
            'url_text' => 'View All',
        );
        return view('manager.attendance.trash')->with($data);
    }

    public function getTrashActivityLog(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Attendance::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Attendance::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Attendance::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();


        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $attributes = (!empty($record->properties['attributes']) ? $record->properties['attributes'] : '');
            $old = (!empty($record->properties['old']) ? $record->properties['old'] : '');
            $current = '<ul class="list-unstyled">';
            //Current
            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    if (is_array($value)) {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    } else {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                }
            }
            $current .= '</ul>';
            //Old
            $oldValue = '<ul class="list-unstyled">';
            if (!empty($old)) {
                foreach ($old as $key => $value) {
                    if (is_array($value)) {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    } else {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                }
            }
            //updated at
            $updated = 'Updated:' . $record->updated_at->diffForHumans() . '<br> At:' . $record->updated_at->isoFormat('llll');
            $oldValue .= '</ul>';
            //Causer
            $causer = isset($record->causer) ? $record->causer : '';
            $type = $record->description;
            $data_arr[] = array(
                "id" => $id,
                "current" => $current,
                "old" => $oldValue,
                "updated" => $updated,
                "causer" => $causer,
                "type" => $type,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }

    public function  notHaveEmployee($request){
        $machineIP = \config('values.machine_ip');
        $machineLoc = \config('values.machine_location');
        $punch_format = Carbon::parse($request->input('punch_time'))->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');
        $upload_format = Carbon::parse($request->input('upload_format'))->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');

//        $format = Carbon::createFromFormat('Y-m-d H:i:sO', $singleAttendance[0]->punch_time, 'UTC')
//                ->setTimezone('Asia/Karachi'),
//
//            $date =   date('Y-m-d H:i:s', strtotime($format)),
        $arr = [
            'punch_time' => $punch_format,
            'upload_time' => $upload_format,
            'area_alias' =>  $request->input('area_alias'),
            'employee_id' => $request->input('employee_id'),
            'employee_code' => $request->input('employee_code'),
            'in_out' => $request->input('in_out'),
            'terminal_alias' => $request->input('terminal_alias'),
            'terminal_sn'=>$request->input('terminal_sn'),
            'machine_ip' => $machineIP,
            'machine_location' => $machineLoc,
            'created_by' => Auth::user()->id,
        ];
//        dd($arr);
        $recordA = Attendance::create($arr);
        if($recordA){
            $record =  GetEmployee::create($arr);
            return $record;

        }

    }
    public function  haveEmployee($request){

        $machineIP = \config('values.machine_ip');
        $machineLoc = \config('values.machine_location');
        $punch_format = Carbon::parse($request->input('punch_time'))->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');
        $upload_format = Carbon::parse($request->input('upload_format'))->setTimezone('Asia/Karachi')->format('Y-m-d H:i:s');

 //        $format = Carbon::createFromFormat('Y-m-d H:i:sO', $singleAttendance[0]->punch_time, 'UTC')
//         ->setTimezone('Asia/Karachi'),
//         $date =   date('Y-m-d H:i:s', strtotime($format)),
        $arr = [
            'punch_time' => $punch_format,
            'upload_time' => $upload_format,
            'area_alias' =>  $request->input('area_alias'),
            'employee_id' => $request->input('employee_id'),
            'employee_code' => $request->input('employee_code'),
            'in_out' => $request->input('in_out'),
            'terminal_alias' => $request->input('terminal_alias'),
            'terminal_sn'=>$request->input('terminal_sn'),
            'machine_ip' => $machineIP,
            'machine_location' => $machineLoc,
            'created_by' => Auth::user()->id,
        ];
        $record = Attendance::create($arr);
        return $record;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function deleteAll(){

        $delAll = Attendance::truncate();

        $messages =  [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.attendance.index');
    }
    public function test(){

        $pgAttendance = new PostGreAttendanceSync();
        $pgAttendance->handle();
//        return back();
        die();
    }
    public function testErp(){
//        dd('123');
//        $erpAttendance = new AttendanceService();
//        $erpAttendance->handle();
//        return back();
        $erpAttendance = new AttendanceServiceV2();
        $erpAttendance->handle();

        die();
    }

}
