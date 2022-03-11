<?php

namespace App\Http\Controllers\Modules\Processing;

use App\Http\Controllers\Controller;

class ErrorController extends Controller
{
    public static function NullResponseError()
    {
        return response([
            'status' => false,
            'code' => 401,
            'error' => 'Unable to connect, please check your internet !'
        ]);
    }

    public static function MmoplApiError($response)
    {
        return response([
            'status' => false,
            'code' => 402,
            'error' => $response->error
        ]);
    }

    public static function PhonePeError($response)
    {
        return response([
            'status' => false,
            'code' => 403,
            'error' => $response->code
        ]);
    }
}
