<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manager\Screen;
use App\Traits\Api\Response;
use Illuminate\Http\Request;


class ScreenController extends Controller
{
    use Response;

    public $data;
    public function getScreenData(Request $request, $uuid )
    {

        $records = Screen::select('*', 'attendances.id as attendance_id', 'attendances.employee_code as attendance_employee_code')
            ->leftJoin('screen_ips', 'screen_ips.screen_id', '=', 'screens.id')
            ->leftJoin('attendances', 'attendances.terminal_alias', '=', 'screen_ips.ip')
            ->leftJoin('employees', 'employees.employee_code', '=', 'attendances.employee_code')
            ->where('screens.uuid', '=', $uuid)
            ->orderBy('attendances.punch_time', 'DESC')
            ->limit(6)
            ->get();

        // For changing display data adjust below parameters.
        $startFromRecordNo = 1;
        $noOfRecordsInHistoryArray= 5;

        $recordActive = '';
        $recordHistory = [];
        $punch_state_image= [];
        foreach ($records as $record){

            if ($record['punch_state'] == 0){

                $punch_state_image [] = [
                  'punch_image'=>
                    "http://localhost/attendance_terminal_zkteco_speedface/public/front/coreui/assets/img/out-1.svg"

];

            }
            if ($record['punch_state'] == 1){

                $punch_state_image[] = [
                    'punch_image'=>
                        "http://localhost/attendance_terminal_zkteco_speedface/public/front/coreui/assets/img/in-1.svg"

                ];
            }

        }
        if(isset($records[0])){
            $recordActive = $records[0]; // if not found check
        }
//        dd($recordActive);

        for ($i = $startFromRecordNo; $i <= $noOfRecordsInHistoryArray; $i++){
               if(isset($records[$i])){
                   $recordHistory[] = $records[$i];  // if not found check

               }
        }

//dd($this->data);
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
                "success" => ["Data Fetched Successfully"],
                "data" => [
                    'active_profile_1'  => $recordActive,
                     'profiles_history' => $recordHistory,
                    'punch_state_image' => $punch_state_image,
                ]
            ];

           $this->setResponse($this->data);
            return $this->getResponse();

    }
}
