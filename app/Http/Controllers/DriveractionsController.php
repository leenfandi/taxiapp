<?php

namespace App\Http\Controllers;
use App\Events\TripStatus;
use App\Models\Driver;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $trip = Trip::where('id' , $request->trip_id)->first();
        $user = User::where('id' , $trip->user_id)->first();
        $server_key = env('FCM_SERVER_KEY');
        $fcm = Http::acceptJson()->withToken($server_key)->post(
            'https://fcm.googleapis.com/fcm/send' ,
              [
                'to' => $user->fcm_token ,
                'notification' =>
                [
                    'title' => 'request accepted' ,
                    'body' => 'Your request has been accepted'
                ]
              ]
                );

        return json_decode($fcm);
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
        $trip = Trip::where('id' , $request->trip_id)->first();
        $user = User::where('id' , $trip->user_id)->first();
        $server_key = env('FCM_SERVER_KEY');
        $fcm = Http::acceptJson()->withToken($server_key)->post(
            'https://fcm.googleapis.com/fcm/send' ,
              [
                'to' => $user->fcm_token ,
                'notification' =>
                [
                    'title' => 'request refused' ,
                    'body' => 'Sorry , Your request has been refused .
                     please try again'
                ]
              ]
                );

        return json_decode($fcm) ;
    }

    public function updateadress(Request $request)
    {
        $driver_id = Auth::guard('driver-api')->id();
        $driver = Driver::where('id' , $driver_id)->first();
        $validator = Validator::make($request->all(),[

            'address'=>'required',
        ]);
        $driver->address = $request->address ;
        $driver->save();
        return response()->json(['msg'=>'address updated succefully']);

    }


}
