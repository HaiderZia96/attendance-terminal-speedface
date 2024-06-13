<?php

namespace App\Http\Controllers\Manager;

use App\Console\Commands\Jobs\FetchEmployeeDetail;
use App\Http\Controllers\Controller;
use App\Models\Manager\Employee;
use App\Models\Manager\GetEmployee;
use App\Models\User;
use App\Services\EmployeeService;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class EmployeeController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_attendance_employee-list', ['only' => ['index','getIndex']]);
        $this->middleware('permission:manager_attendance_employee-create', ['only' => ['create','store']]);
        $this->middleware('permission:manager_attendance_employee-show', ['only' => ['show']]);
        $this->middleware('permission:manager_attendance_employee-sync', ['only' => ['test']]);
        $this->middleware('permission:manager_attendance_employee-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:manager_attendance_employee-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_attendance_employee-sync-to-erp', ['only' => ['empErp']]);
        $this->middleware('permission:manager_attendance_employee-activity-log', ['only' => ['getActivity','getActivityLog','getTrashActivity','getTrashActivityLog']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

//        $dataA = $emp->handle();
////        $data= json_decode( $dataA, true);
//        dd($dataA);
        $data = [
            'page_title' => 'Employee',
            'p_title' => 'Employee',
            's_title' => 'Employee',
            'p_summary' => 'List of Employees',
            'p_description' => null,
            'url' => route('manager.employee.create'),
            'url_text' => 'Add New',
            'emp_url' => route('manager.employee.test'),
            'emp_url_text' => 'Employee Sync',
            'trash' => route('manager.get.employee-activity-trash'),
            'trash_text' => 'View Trash',

        ];
//        dd($departments);
        return view('manager.employee.index')->with($data);
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

        // Status
        if (!is_null($request->get('status'))) {
            $status = $request->get('status');
            $var = ['employees.status', 'like', '%' . $status . '%'];
            array_push($where, $var);
        }

        // Role Status
        if(!empty($request->get('role_status'))){
            $var = ['employees.role_status', '=',$request->get('role_status') ];
            array_push($where,$var);
        }


        $totalRecords = Employee::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'employees.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('employees.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.department', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records with filter
        $totalRecordswithFilter = Employee::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'employees.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('employees.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.department', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records
        $records = Employee::orderBy($columnName, $columnSortOrder)
            ->leftJoin('users', 'users.id', '=', 'employees.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('employees.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('employees.department', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->select('employees.*')
            ->orderBy('id', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $employee_code = $record->employee_code;
            $name = $record->name;
            $image = $record->image;
            $campus = $record->campus;
            $student_reg_no = $record->student_reg_no;
            $designation = $record->designation;
            $department = $record->department;
            $status = $record->status;
            $role_status = $record->role_status;
            $st_in_day1 = $record->st_in_day1;
            $st_in_day2 = $record->st_in_day2;
            $st_in_day3 = $record->st_in_day3;
            $st_in_day4 = $record->st_in_day4;
            $st_in_day5 = $record->st_in_day5;
            $st_in_day6 = $record->st_in_day6;
            $st_in_day7 = $record->st_in_day7;
            $st_out_day1 = $record->st_out_day1;
            $st_out_day2 = $record->st_out_day2;
            $st_out_day3 = $record->st_out_day3;
            $st_out_day4 = $record->st_out_day4;
            $st_out_day5 = $record->st_out_day5;
            $st_out_day6 = $record->st_out_day6;
            $st_out_day7 = $record->st_out_day7;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "image" => $image,
                "employee_code" => $employee_code,
                "student_reg_no" => $student_reg_no,
                "campus" => $campus,
                "designation" => $designation,
                "department" => $department,
                "status" => $status ,
                "role_status" => $role_status,
                "st_in_day1" =>$st_in_day1,
                "st_in_day2" =>$st_in_day2,
                "st_in_day3" =>$st_in_day3,
                "st_in_day4" =>$st_in_day4,
                "st_in_day5" =>$st_in_day5,
                "st_in_day6" =>$st_in_day6,
                "st_in_day7" =>$st_in_day7,
                "st_out_day1" =>$st_out_day1,
                "st_out_day2" =>$st_out_day2,
                "st_out_day3" =>$st_out_day3,
                "st_out_day4" =>$st_out_day4,
                "st_out_day5" =>$st_out_day5,
                "st_out_day6" =>$st_out_day6,
                "st_out_day7" =>$st_out_day7,
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
            'page_title' => 'Employee',
            'p_title' => 'Employee',
            'p_summary' => 'Add Employee',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.employee.store'),
            'url' => route('manager.employee.index'),
            'url_text' => 'View All',
            'enctype' => 'multipart/form-data', // (Default)Without attachment
        );
        return view('manager.employee.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'employee_code' => 'required|unique:employees|string',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Image
        $image = null;
        if ($request->hasFile('cropper_image')) {
            // Image
            $cImage = $request->file('image');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time()   . rand(0, 999999) . '-' . $imageOriginalNameSluggy.'.'.$imageExtension;

            $basePath = 'private';
            $imagePath = $basePath . '/Employees';
            $attachmentPath = $imagePath . '/Profile';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $imagefileName = $cImageFileName;
            $image = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
        }
        $arr = [
            'employee_code' => $request->input('employee_code'),
            'name' => $request->input('name'),
            'image' => $image,
            'designation' => $request->input('designation'),
            'department' => $request->input('department'),
            'campus' => $request->input('campus'),
            'status' => $request->input('status'),
            'created_by' => Auth::user()->id,
        ];
//        dd($arr);
        $record = Employee::create($arr);
        if ($record) {
            $messages = [
                array(
                    'message' => 'Record created successfully',
                    'message_type' => 'success'
                ),
            ];
            Session::flash('messages', $messages);

            return redirect()->route('manager.employee.index');
        } else {
            abort(404, 'NOT FOUND');
        }
    }

//    public function store(Request $request)
//    {
//        $employee = new Employee();
//        $this->validate($request, [
//             'image' => 'required',
//        ]);
//
//        $arr = [
////            'emp_id' => $request->input('emp_id'),
////            'name' => $request->input('name'),
//            'image' => $request->input('image'),
////            'doj' => $request->input('doj'),
////            'nic' => $request->input('nic'),
////            'designation' => $request->input('designation'),
////            'status' => $request->input('status'),
//
//        ];
//
////        $employeeDetail->emp_id = $arr['emp_id'];
////        $employeeDetail->name    = $arr['name'];
////        $employeeDetail->nic     = $arr['nic'];
////        $employeeDetail->doj       = $arr['doj'];
////        $employeeDetail->designation = $arr['designation'];
////        $employeeDetail->status   = $arr['status'];
//        $employee->image   = $arr['image'];
//        $employee->save();
//
//
//
////        $record = Employee::create($arr);
////        return response()->json(['message' => 'Data Saved Successfully', 'employee' => $employeeDetail], 201);
//
//    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Employee::find($id);
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('Config')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name'=>$record->name]])
            ->log('viewed');
        $data = array(
            'page_title'=>'Employee',
            'p_title'=>'Employee',
            'p_summary'=>'Show Employee',
            'p_description'=>null,
            'method' => 'POST',
            'action' => route('manager.employee.update',$record->id),
            'url'=>route('manager.employee.index'),
            'url_text'=>'View All',
            'data'=>$record,
            'enctype' => 'application/x-www-form-urlencoded',
        );
        return view('manager.employee.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = Employee::where('id', $id)->first();

        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $data = array(
            'page_title' => 'Employee',
            'p_title' => 'Employee',
            'p_summary' => 'Edit Employee',
            'p_description' => null,
            'method' => 'POST',
            'action' => route('manager.employee.update', $record->id),
            'url' => route('manager.employee.index'),
            'url_text' => 'View All',
            'data' => $record,
            'enctype' => 'multipart/form-data',
        );
//        dd($data);
        return view('manager.employee.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //        dd($request);
        $record = Employee::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        $this->validate($request, [
            'name' => 'required',
            'employee_code' => 'required',
        ]);

        if ($request->hasFile('cropper_image')) {
            // Image
            $cImage = $request->file('image');
            $imageOriginalName = $cImage->getClientOriginalName();
            $imageName = pathinfo($imageOriginalName, PATHINFO_FILENAME);
            $imageExtension = $cImage->getClientOriginalExtension();
            $imageOriginalNameSluggy = Str::slug($imageName);
            $cImageFileName = time()   . rand(0, 999999) . '-' . $imageOriginalNameSluggy.'.'.$imageExtension;
            $basePath = 'private';
            $departmentPath = $basePath . '/Employees';
            $attachmentPath = $departmentPath . '/Profile';
            $monthlyAttachmentsPath = $attachmentPath . '/' . date('Y') . '/' . date('m');
            $imagefileName = $cImageFileName;
            $image = $request->file('cropper_image')->storeAs(
                $monthlyAttachmentsPath,
                $cImageFileName
            );
            //Unlink previous image
            if (isset($record) && $record->image) {
                $prevImage = Storage::disk('private')->path('Employees/Profile/'.$record->image);
                if (File::exists($prevImage)) { // unlink or remove previous image from folder
                    File::delete($prevImage);
                }
                $arr['image'] = $image;
            }
        }
        else{
            $image = $record->image;
        }

        $arr = [
            'employee_code' => $request->input('employee_code'),
            'name' => $request->input('name'),
            'image' => $image,
            'designation' => $request->input('designation'),
            'department' => $request->input('department'),
            'campus' => $request->input('campus'),
            'status' => $request->input('status'),
            'updated_by' => Auth::user()->id,
        ];
//        dd($arr);
        $record->update($arr);
        $messages = [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('manager.employee.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = Employee::find($id);
        if (empty($record)) {
            abort(404, 'NOT FOUND');
        }
        // Profile Image Remove
        $image_path = Storage::disk('private')->path('Employees/Profile/' . $record->image);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }

        $record->delete();
        $messages = [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);
        return redirect()->route('manager.employee.index');
    }
    public function getActivity(string $id)
    {
        //Data Array
        $data = array(
            'page_title' => 'Employee Activity',
            'p_title' => 'Employee Activity',
            'p_summary' => 'Show Employee Activity',
            'p_description' => null,
            'url' => route('manager.employee.index'),
            'url_text' => 'View All',
            'id' => $id,
        );
        return view('manager.employee.activity')->with($data);
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
            ->where('activity_log.subject_type', Employee::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Employee::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.subject_id', $id)
            ->where('activity_log.subject_type', Employee::class)
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
            'page_title' => 'Employee Activity',
            'p_title' => 'Employee Activity',
            'p_summary' => 'Show Employee Trashed Activity',
            'p_description' => null,
            'url' => route('manager.employee.index'),
            'url_text' => 'View All',
        );
        return view('manager.employee.trash')->with($data);
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
            ->where('activity_log.subject_type', Employee::class)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Employee::class)
            ->where(function ($q) use ($searchValue) {
                $q->where('activity_log.description', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*', 'users.name as causer')
            ->leftJoin('users', 'users.id', 'activity_log.causer_id')
            ->where('activity_log.event', 'deleted')
            ->where('activity_log.subject_type', Employee::class)
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
    public function test(){
//        $emp = new EmployeeService();
//        $emp->handle();
//        return back();

        $emp = new ProfileService();
        $emp->handle();
        die();
    }
    public function empErp(string $id)
    {
        $appUser = User::select('*')->where('email','=', 'app@app.com')->first();
        $record = Employee::select('employees.*')
            ->where('id', '=' ,$id )
            ->first();
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }

        $arr = [
            'employee_code' =>  $record->employee_code,
            'created_by' => $appUser->id,
        ];

        $record = GetEmployee::updateOrCreate(
            [
                'employee_code' =>  $record->employee_code
            ]
            ,$arr);

        $messages =  [
            array(
                'message' => 'Employee Sent to Erp.',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.employee.index');
    }
}
