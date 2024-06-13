<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ScreenController extends Controller
{


    public function index(Request $request) {
       $uuid =  $request->route('uuid');
        $profiles = '{
        "active_profile_1" : {
            "code" : "109288",
            "url_profile_image": "http://localhost/attendance_terminal_zkteco_speedface/public/front/coreui/assets/img/avatars/dummy-user.png",
            "name": "Salik Munir",
            "designation": "web developer",
            "Department": "WEB",
            "url_in_out": "http://localhost/attendance_terminal_zkteco_speedface/public/front/coreui/assets/img/in-1.svg",
            "border_in_out": "in",
            "alert": "late",
            "attendance_time": "12/02/2021 11:11:11",
            "stu_emp": 1
        }

    }';

//        $response = json_decode($profiles, true);
//        dd($response);
        $data = [
            'profiles' => $profiles,
            'uuid' => $uuid
        ];


//       dd($data);
        return view('front.screen')->with($data);
    }

    public function getUserImage($regCode)
    {
        $path = Storage::disk('private')->path('Users/Profile/' . $regCode.".jpg");
        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
            return $response;
        }
        else{
            abort(404, 'NOT FOUND');
        }

    }

}
