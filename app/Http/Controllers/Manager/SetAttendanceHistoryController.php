<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\SetAttendanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SetAttendanceHistoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_attendance_set-attendance-histories-list', ['only' => ['index','getIndex']]);
        $this->middleware('permission:manager_attendance_set-attendance-histories-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_attendance_set-attendance-histories-delete-all', ['only' => ['deleteAll']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'page_title' => 'Set Attendance Log',
            'p_title' => 'Set Attendance Log',
            's_title' => 'Set Attendance Log',
            'p_summary' => 'List of Set Attendances Logs',
            'p_description' => null,
            'url' => route('manager.set-attendance-histories.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.set-attendance-histories-activity-trash'),
            'trash_text' => 'View Trash',
            'delete_all' => route('manager.set-attendance-histories-delete-all'),
            'delete_all_text' => 'Delete All',
        ];
//        dd($departments);
        return view('manager.set-attendance-history.index')->with($data);
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
            $var = ['set_attendance_histories.status_code', 'like', '%' . $status . '%'];
            array_push($where, $var);
        }

        if (!empty($request->get('start_date'))) {
            $var = ['set_attendance_histories.created_at', '>=', $request->get('start_date') . ' 00:00:00'];
            array_push($where, $var);
        }
        if (!empty($request->get('end_date'))) {
            $var = ['set_attendance_histories.created_at', '<=', $request->get('end_date') . ' 23:59:59'];
            array_push($where, $var);
        }

        $totalRecords = SetAttendanceHistory::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'set_attendance_histories.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('set_attendance_histories.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records with filter
        $totalRecordswithFilter = SetAttendanceHistory::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'set_attendance_histories.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('set_attendance_histories.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records
        $records = SetAttendanceHistory::orderBy($columnName, $columnSortOrder)
            ->leftJoin('users', 'users.id', '=', 'set_attendance_histories.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('set_attendance_histories.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->select('set_attendance_histories.*')
            ->orderBy('id', 'DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
//         dd($records);
        $data_arr = array();

        foreach ($records as $record) {
            $created_at_date =   date('Y-m-d H:i:s', strtotime($record->created_at));

            $id = $record->id;
            $employee_code = $record->employee_code;
            $log = $record->log;
            $status_code = $record->status_code;
            $created_at = $created_at_date;

            $data_arr[] = array(
                "id" => $id,
                "employee_code" => $employee_code,
                "log" =>  $log,
                'status_code' => $status_code,
                "created_at" => $created_at
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $record = SetAttendanceHistory::select('set_attendance_histories.*')
            ->where('id', '=' ,$id )
            ->first();
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }
        $record->delete();

        $messages =  [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.set-attendance-histories.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function deleteAll(){

        $delAll = SetAttendanceHistory::truncate();

        $messages =  [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.set-attendance-histories.index');
    }
}
