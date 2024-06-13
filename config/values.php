<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Attendance Hub URL
    |--------------------------------------------------------------------------
    */

    'hub' => env('ATTENDANCE_HUB_URL', 'http://attendance-hub.tuf.edu.pk/'),
    'machine_ip' => env('MACHINE_IP', '172.15.10.23'),
    'machine_location' => env('MACHINE_LOCATION', 'EW'),
    'token' => env('ATTENDANCE_HUB_TOKEN', 'token'),
    ];
