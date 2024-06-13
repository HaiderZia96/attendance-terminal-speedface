<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\Screen;
use App\Models\Manager\ScreenIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Models\Activity;

class ScreenIpController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:manager_attendance_screen-ip-list', ['only' => ['index','getIndex']]);
        $this->middleware('permission:manager_attendance_screen-ip-create', ['only' => ['create','store']]);
        $this->middleware('permission:manager_attendance_screen-ip-show', ['only' => ['show']]);
        $this->middleware('permission:manager_attendance_screen-ip-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:manager_attendance_screen-ip-delete', ['only' => ['destroy']]);
        $this->middleware('permission:manager_attendance_screen-ip-activity-log', ['only' => ['getActivity','getActivityLog','getTrashActivity','getTrashActivityLog']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index($sid)
    {

        $data=[
            'page_title'=>'Screen IP',
            'p_title'=>'Screen IP',
            'p_summary'=>'List of Screen IP',
            'p_description'=>null,
            'url'=>route('manager.screen.screen-ip.create',$sid),
            'url_text'=>'Add New',
            'trash'=>route('manager.get.screen-ip-activity-trash', $sid),
            'sid' => $sid,
            'trash_text'=>'View Trash',
        ];
//        dd($data);
        return view('manager.screen-ip.index')->with($data);
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request,$sid)
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
        $totalRecords = ScreenIp::select('screen_ips.*')
            ->where('screen_id', '=', $sid)
            ->count();
        // Total records with filter
        $totalRecordswithFilter = ScreenIp::select('screen_ips.*')
            ->where('screen_id', '=', $sid)
            ->where('ip', 'like', '%' .$searchValue . '%')
            ->count();
        // Fetch records
        $records = ScreenIp::select('screen_ips.*')
            ->where('screen_id', '=', $sid)
            ->where('ip', 'like', '%' .$searchValue . '%')
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName,$columnSortOrder)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $ip = $record->ip;
            $type = $record->type;
            if(isset($record['screenID']['name'])){
                $screenID = $record['screenID']['name'];
            }
            else{
                $screenID = "";
            }
            if(isset($record['screenID']['uuid'])){
                $screenUuid = $record['screenID']['uuid'];
            }
            else{
                $screenUuid = "";
            }


            $data_arr[] = array(
                "id" => $id,
                "ip" => $ip,
                "screen_id" => $screenID,
                "sid" => $sid,
                "screenUuid" => $screenUuid,
                "type" => $type
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
    public function create($sid)
    {
        $data = array(
            'page_title'=>'Screen IP',
            'p_title'=>'Screen IP',
            'p_summary'=>'Add Screen IP',
            'p_description'=>null,
            'method' => 'POST',
            'action' => route('manager.screen.screen-ip.store',$sid),
            'url'=>route('manager.screen.screen-ip.index',$sid),
            'sid' => $sid,
            'url_text'=>'View All',
            'enctype' => 'application/x-www-form-urlencoded', // With attachment like file or images in form
        );
        return view('manager.screen-ip.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$sid)
    {

        $messages = [
            'ip.unique' => 'This IP is already attached with any screen. One IP could be attache with one screen at a time.'
        ];

        $this->validate($request, [
            'ip' => 'required|unique:screen_ips,ip',
            'type' => 'required|integer|between:0,2',
        ], $messages);

        //
        $arr =  [
            'ip' => $request->input('ip'),
            'type' => $request->input('type'),
            'screen_id' => $sid,
        ];

        $record = ScreenIp::create($arr);
        $messages =  [
            array(
                'message' => 'Record created successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.screen.screen-ip.index',$sid);
    }

    /**
     * Display the specified resource.
     */
    public function show($sid,string $id)
    {
//        dd($sid);

        $record = ScreenIp::select('screen_ips.*','screens.id as screen_id', 'screens.name as screen_name')
            ->leftJoin('screens', 'screens.id', '=', 'screen_ips.screen_id')
            ->where('screen_ips.id', '=' ,$id )
            ->first();
//        dd($record);
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }
        // Add activity logs
        $user = Auth::user();
        activity('Screen')
            ->performedOn($record)
            ->causedBy($user)
            ->event('viewed')
            ->withProperties(['attributes' => ['name'=>$record->name]])
            ->log('viewed');
        //Data Array
        $data = array(
            'page_title'=>'Screen IP',
            'p_title'=>'Screen IP',
            'p_summary'=>'Show Screen IP',
            'p_description'=>null,
            'method' => 'POST',
            'action' => route('manager.screen.screen-ip.update',[$record->id, $sid]),
            'url'=>route('manager.screen.screen-ip.index',$sid),
            'url_text'=>'View All',
            'data'=>$record,
            'enctype' => 'application/x-www-form-urlencoded',
        );
//        dd($data);
        return view('manager.screen-ip.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sid,string $id)
    {
        $record = ScreenIp::select('screen_ips.*','screens.id as screen_id', 'screens.name as screen_name')
            ->leftJoin('screens', 'screens.id', '=', 'screen_ips.screen_id')
            ->where('screen_ips.id', '=' ,$id )
            ->first();
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }
        $data = array(
            'page_title'=>'Screen IP',
            'p_title'=>'Screen IP',
            'p_summary'=>'Edit Screen IP',
            'p_description'=>null,
            'method' => 'POST',
            'action' => route('manager.screen.screen-ip.update',[$record->id, $sid]),
            'url'=>route('manager.screen.screen-ip.index',$sid),
            'url_text'=>'View All',
            'data'=>$record,
            'enctype' => 'application/x-www-form-urlencoded', // With attachment like file or images in form
        );
        return view('manager.screen-ip.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, $sid)
    {
//        dd($id);
        $record = ScreenIp::select('screen_ips.*')
            ->where('id', '=' ,$id )
            ->first();
        if (empty($record)){
            abort(404, 'NOT FOUND');
        }

        $messages = [
            'ip.unique' => 'This IP is already attached with any screen. One IP could be attache with one screen at a time.'
        ];

        $this->validate($request, [
            'ip' => 'required|unique:screen_ips,ip,'.$id,
            'type' => 'required|integer|between:0,2',
            ], $messages);

        $arr =  [
            'ip' => $request->input('ip'),
            'type' => $request->input('type'),
            'screen_id' => $sid,
        ];
        $record->update($arr);
        $messages =  [
            array(
                'message' => 'Record updated successfully',
                'message_type' => 'success'
            ),
        ];
        Session::flash('messages', $messages);

        return redirect()->route('manager.screen.screen-ip.index',$sid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sid, string $id)
    {
//        dd($id);
        $record = ScreenIp::select('screen_ips.*')
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

        return redirect()->route('manager.screen.screen-ip.index',$sid);
    }
    public function getScreenIndexSelect(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Screen::select('screens.id as id', 'screens.name as name')
                ->where(function ($q) use ($search) {
                    $q->where('screens.name', 'like', '%' . $search . '%');
                })
                ->get();
        }

        return response()->json($data);

    }
    /**
     * Display the specified resource Activity.
     * @param  String_  $id
     * @return \Illuminate\Http\Response
     */
    public function getActivity($sid,string $id)
    {
//        dd($sid);
        //Data Array
        $data = array(
            'page_title'=>'Screen IP Activity',
            'p_title'=>'Screen IP Activity',
            'p_summary'=>'Show IP Screen Activity',
            'p_description'=>null,
            'url'=>route('manager.screen.screen-ip.index',$sid),
            'url_text'=>'View All',
            'id'=>$id,
        );
        return view('manager.screen-ip.activity')->with($data);
    }
    /**
     * Display the specified resource Activity Logs.
     * @param  String_  $id
     * @return \Illuminate\Http\Response
     */
    public function getActivityLog(Request $request,string $id)
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
        $totalRecords = Activity::select('activity_log.*','users.name as causer')
            ->leftJoin('users','users.id','activity_log.causer_id')
            ->leftJoin('configs','configs.id','activity_log.subject_id')
            ->where('activity_log.subject_type',ScreenIp::class)
            ->where('activity_log.subject_id',$id)
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*','users.name as causer')
            ->leftJoin('users','users.id','activity_log.causer_id')
            ->leftJoin('configs','configs.id','activity_log.subject_id')
            ->where('activity_log.subject_id',$id)
            ->where('activity_log.subject_type',ScreenIp::class)
            ->where(function ($q) use ($searchValue){
                $q->where('activity_log.description', 'like', '%' .$searchValue . '%')
                    ->orWhere('users.name', 'like', '%' .$searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*','users.name as causer')
            ->leftJoin('users','users.id','activity_log.causer_id')
            ->leftJoin('configs','configs.id','activity_log.subject_id')
            ->where('activity_log.subject_id',$id)
            ->where('activity_log.subject_type',ScreenIp::class)
            ->where(function ($q) use ($searchValue){
                $q->where('activity_log.description', 'like', '%' .$searchValue . '%')
                    ->orWhere('users.name', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName,$columnSortOrder)
            ->get();


        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $attributes = (!empty($record->properties['attributes']) ? $record->properties['attributes'] : '');
            $old = (!empty($record->properties['old']) ? $record->properties['old'] : '');
            $current='<ul class="list-unstyled">';
            //Current
            if (!empty($attributes)){
                foreach ($attributes as $key => $value){
                    if (is_array($value)) {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                    else{
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                }
            }
            $current.='</ul>';
            //Old
            $oldValue='<ul class="list-unstyled">';
            if (!empty($old)){
                foreach ($old as $key => $value){
                    if (is_array($value)) {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                    else{
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                }
            }
            //updated at
            $updated = 'Updated:'.$record->updated_at->diffForHumans().'<br> At:'.$record->updated_at->isoFormat('llll');
            $oldValue.='</ul>';
            //Causer
            $causer = isset($record->causer) ? $record->causer : '';
            $type= $record->description;
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
//        dd($response);

        echo json_encode($response);
        exit;
    }
    /**
     * Display the trash resource Activity.
     * @return \Illuminate\Http\Response
     */
    public function getTrashActivity($sid)
    {
        //Data Array
        $data = array(
            'page_title'=>'Screen IP Activity',
            'p_title'=>'Screen IP Activity',
            'p_summary'=>'Show Screen IP Trashed Activity',
            'p_description'=>null,
            'url'=>route('manager.screen.screen-ip.index',$sid),
            'url_text'=>'View All',
        );
        return view('manager.screen-ip.trash')->with($data);
    }
    /**
     * Display the trash resource Activity Logs.
     * @param  String_  $id
     * @return \Illuminate\Http\Response
     */
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
        $totalRecords = Activity::select('activity_log.*','users.name as causer')
            ->leftJoin('users','users.id','activity_log.causer_id')
            ->leftJoin('configs','configs.id','activity_log.subject_id')
            ->where('activity_log.subject_type',ScreenIp::class)
            ->where('activity_log.event','deleted')
            ->count();

        // Total records with filter
        $totalRecordswithFilter = Activity::select('activity_log.*','users.name as causer')
            ->leftJoin('users','users.id','activity_log.causer_id')
            ->leftJoin('configs','configs.id','activity_log.subject_id')
            ->where('activity_log.subject_type',ScreenIp::class)
            ->where('activity_log.event','deleted')
            ->where(function ($q) use ($searchValue){
                $q->where('activity_log.description', 'like', '%' .$searchValue . '%')
                    ->orWhere('users.name', 'like', '%' .$searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Activity::select('activity_log.*','users.name as causer')
            ->leftJoin('users','users.id','activity_log.causer_id')
            ->leftJoin('configs','configs.id','activity_log.subject_id')
            ->where('activity_log.subject_type',ScreenIp::class)
            ->where('activity_log.event','deleted')
            ->where(function ($q) use ($searchValue){
                $q->where('activity_log.description', 'like', '%' .$searchValue . '%')
                    ->orWhere('users.name', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName,$columnSortOrder)
            ->get();


        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $attributes = (!empty($record->properties['attributes']) ? $record->properties['attributes'] : '');
            $old = (!empty($record->properties['old']) ? $record->properties['old'] : '');
            $current='<ul class="list-unstyled">';
            //Current
            if (!empty($attributes)){
                foreach ($attributes as $key => $value){
                    if (is_array($value)) {
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                    else{
                        $current .= '<li>';
                        $current .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $current .= '</li>';
                    }
                }
            }
            $current.='</ul>';
            //Old
            $oldValue='<ul class="list-unstyled">';
            if (!empty($old)){
                foreach ($old as $key => $value){
                    if (is_array($value)) {
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                    else{
                        $oldValue .= '<li>';
                        $oldValue .= '<i class="fas fa-angle-right"></i> <em></em>' . $key . ': <mark>' . $value . '</mark>';
                        $oldValue .= '</li>';
                    }
                }
            }
            //updated at
            $updated = 'Updated:'.$record->updated_at->diffForHumans().'<br> At:'.$record->updated_at->isoFormat('llll');
            $oldValue.='</ul>';
            //Causer
            $causer = isset($record->causer) ? $record->causer : '';
            $type= $record->description;
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
}
