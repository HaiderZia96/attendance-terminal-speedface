<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\GetEmployeeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GetEmployeeHistoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_attendance_get-employee-history-list', ['only' => ['index','getIndex']]);
        $this->middleware('permission:manager_attendance_get-employee-history-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_attendance_get-employee-history-delete-all', ['only' => ['deleteAll']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $data = [
            'page_title' => 'Get Employee Log',
            'p_title' => 'Get Employee Log',
            's_title' => 'Get Employee Log',
            'p_summary' => 'List of Get Employees Logs',
            'p_description' => null,
            'url' => route('manager.get-employee-histories.create'),
            'url_text' => 'Add New',
            'trash' => route('manager.get.get-employee-histories-activity-trash'),
            'trash_text' => 'View Trash',
            'delete_all' => route('manager.get-employee-histories-delete-all'),
            'delete_all_text' => 'Delete All',
            'start_date'=> $start_date,
            'end_date'=> $end_date,
        ];
//        dd($departments);
        return view('manager.get-employee-history.index')->with($data);
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
            $var = ['get_employee_histories.status_code', 'like', '%' . $status . '%'];
            array_push($where, $var);
        }
//        if(!empty($request->get('status'))){
//            $var = ['get_employee_histories.status_code', '=',$request->get('status') ];
//            array_push($where,$var);
//        }
        if (!empty($request->get('start_date'))) {
            $var = ['get_employee_histories.created_at', '>=', $request->get('start_date') . ' 00:00:00'];
            array_push($where, $var);
        }
        if (!empty($request->get('end_date'))) {
            $var = ['get_employee_histories.created_at', '<=', $request->get('end_date') . ' 23:59:59'];
            array_push($where, $var);
        }

        $totalRecords = GetEmployeeHistory::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'get_employee_histories.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('get_employee_histories.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
//            ->whereBetween('get_employee_histories.created_at', [$start_date, $end_date])
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records with filter
        $totalRecordswithFilter = GetEmployeeHistory::select('count(*) as allcount')
            ->leftJoin('users', 'users.id', '=', 'get_employee_histories.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('get_employee_histories.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->where($where)
            ->orderBy('id', 'DESC')
            ->count();
        // Total records
        $records = GetEmployeeHistory::orderBy($columnName, $columnSortOrder)
            ->leftJoin('users', 'users.id', '=', 'get_employee_histories.created_by')
            ->where(function ($q) use ($searchValue) {
                $q->where('get_employee_histories.employee_code', 'like', '%' . $searchValue . '%')
                    ->orWhere('users.name', 'like', '%' . $searchValue . '%');
            })
            ->select('get_employee_histories.*')
            ->where($where)
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
        $record = GetEmployeeHistory::select('get_employee_histories.*')
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

        return redirect()->route('manager.get-employee-histories.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function deleteAll(){

        $delAll = GetEmployeeHistory::truncate();

        $messages =  [
            array(
                'message' => 'Record deleted successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.get-employee-histories.index');
    }

    public function filterData(Request $request){
        $from_date =$request->from_date;
        $to_date =$request->to_date;

        GetEmployeeHistory::whereDate('created_at','>=', $from_date)
            ->whereDate('created_at','<=', $to_date)
            ->get();

        return redirect()->route('manager.get-employee-histories.index');
    }

}
