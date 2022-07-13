<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriveractionsController extends Controller
{

    public function __construct()
    {
     $this->middleware('App\Http\Middleware\DriverAuth:driver-api');

    }


    public function activate()
    {
        $driver = Auth::guard('driver-api')->user();

        if($driver->status == 1)
        {
            $status = 0;
        }
        else {
            $status = 1;
        }

        //$values = array('status' => $status);
        Driver::where('id',$driver->id)->update(['status' => $status]);

        return response()->json(['msg'=>'driver update succefully']);
    }

    public function accepteOrder(Request $request)
    {
        $validator =Validator::make($request->all(),[

            'trip_id'=>'required|numeric',
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),422);
        }

        Trip::where('id' , $request->trip_id)->update(['status' => 1]);

        return response()->json(['msg'=>'Trip accepted']);
    }

    public function refusalOrder(Request $request)
    {
        $validator =Validator::make($request->all(),[

            'trip_id'=>'required|numeric',
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),422);
        }

        Trip::where('id' , $request->trip_id)->update(['status' => -1]);

        return response()->json(['msg'=>'Trip refusal']);
    }
}
