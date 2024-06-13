<?php

namespace App\Traits\Api;

use Illuminate\Support\Facades\Config;

trait Response
{
    public $response;
    public $status;

    public function setResponse($data)
    {

        $appName = Config::get('apiResponse.message' . '.' . $data["status_code"]);

        if (isset($data["code"])) {
            $appName = Config::get('apiResponse.message' . '.' . $data["code"]);
        }


        $this->status = ['status_code' => (!isset($data['status_code'])) ? '' : $data['status_code'],];

        $this->response = [
            'status_code' => (!isset($data['code'])) ? $data['status_code'] : $data['code'],
            'message' => (!isset($data['response'])) ? [] : $appName,
            'noti' => [
                'success'=>(!isset($data['success']))?[]:$data['success'],
                'error'=>(!isset($data['error']))?[]:$data['error'],
                'info'=>(!isset($data['info']))?[]:$data['info'],
                'warning'=>(!isset($data['warning']))?[]:$data['warning']
            ],
            'data' => (!isset($data['data'])) ? [] : $data['data']

        ];

        return $this->response;

    }

    public function getResponse()
    {
        return response()->json($this->response, $this->status['status_code']);

    }


}
