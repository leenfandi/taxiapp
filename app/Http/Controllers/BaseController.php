<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result,$message)
    {
        $response = [
            'message' => $message,
             'data'=>$result
        ];
        return response()->json($response, 200);

    }

    public function sendError($error, $errorMessage = [])
    {
        $response = [
            'message' => $error
        ];
        if (!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }
        return response()->json($response, 404);

    }
    public function sendResponse_2($result)
    {

        return response()->json($result, 200);

    }
}
