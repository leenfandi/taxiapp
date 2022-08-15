<?php

namespace App\Http\Controllers;

use App\Events\NewTrip;
use App\Models\Driver;
use App\Models\User;
use App\Models\Trip;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Support\Facades\Http;

class AddTripController extends Controller
{
public function store( Request $request){
    $input = $request->all();
    $trip = new Trip();

    $user_id= Auth::guard('api')->user()->id;
    $trip->user_id = $user_id;
     $trip->first_location= $input['first_location'] ;
     $trip->start_time= $input['start_time'] ;
     $trip->end_time= $input['end_time'] ;
     $trip->lat1 = $input['lat1'];
     $trip->long1 = $input['long1'];
     $trip->end_location= $input['end_location'] ;
     $trip->lat2 = $input['lat2'];
     $trip->long2 = $input['long2'];
     $trip->note= $input['note'] ;
     $trip->driver_id = $input['driver_id'];

      $trip->save();


    $Radius = 6371 ;
    $deglat = deg2rad($request->lat2 - $request->lat1);
    $deglong = deg2rad($request->long2 - $request->long1);
    $a = sin($deglat/2) * sin($deglat/2) + cos(deg2rad($request->lat1))
    * cos(deg2rad($request->lat2)) * sin($deglong/2) * sin($deglong/2);
    $c = 2 *atan2(sqrt($a),sqrt(1-$a));
    $dist = $Radius * $c ;

    $price = $dist * 3300;

    $time = $dist * 0.7 ;

     return response()->json([
            'messege'=> 'Trip store seccesfuly ',
            'trip_id' => $trip->id ,
            'distance'=>  round($dist,2) ,
            'price' => round($price,-1) ,
            'time' => round($time)


    ]);

}


public function delete( $id){
    $trip = Trip::find($id);
    $result = $trip->delete();
    if($result){
        return response()->json([
            'message'=>' A Trip Deleted Successfully'
        ],201);
     } else{
        return response()->json([
            'message'=>'Trip Not Deleted '
        ],400);
        }
}
public function updatetrip (  Request $request){
    $input = $request->all();
    $user_id= Auth::guard('api')->user()->id;
    $trip =Trip::where( 'user_id',$user_id)->latest()->first();
    $validator = validator($input, [

        'end_location'=>'string',
        'lat2' => 'numeric' ,
        'long2' => 'numeric'

    ]);
    if ($validator->fails()) {
        return response()->json(['error'=>$validator->errors()]);
    }


    if($request->exists('end_location')){
    $trip->end_location= $input['end_location'] ;
    $trip->lat2 = $input['lat2'];
    $trip->long2 = $input['long2'];
    }
    $trip->save();

    $Radius = 6371 ;
    $deglat = deg2rad($request->lat2 - $trip->first_lat);
    $deglong = deg2rad($request->long2 - $trip->first_long);
    $a = sin($deglat/2) * sin($deglat/2) + cos(deg2rad($trip->first_lat))
    * cos(deg2rad($request->lat2)) * sin($deglong/2) * sin($deglong/2);
    $c = 2 *atan2(sqrt($a),sqrt(1-$a));
    $dist = $Radius * $c ;

    $price = $dist * 3300;

    $time = $dist * 0.7 ;
    return response()->json([
    'msg'=>'trip update succefully' ,
    'trip_id' => $trip->id ,
    'distance'=>  round($dist,2) ,
    'price' => round($price,-1) ,
    'time' => round($time)
]);
}

public function getDriverNearby( Request $request){

    $input = $request->all();
     $validator = validator($input, [

        'first_location'=>'required|string',

    ]);
    if ($validator->fails()) {
        return response()->json(['error'=>$validator->errors()]);
    }

    $drivers = Driver::where('status' , 1)->where('address',$request->first_location)->get();
        $response = [];
        $i = 1;
        foreach($drivers as $driver){
            if ( is_null($driver->image) )
            {
             $image = 'null';
            }
             else{
                 $image = asset($driver->image);
                }

            $response[$i] =
                ['id' => $driver->id,
            'name' => $driver->name,
            'gender' => $driver->gender,
            'typeofcar' => $driver->typeofcar,
            'image' => $image ,
            'number' => $driver->number,
            'address' =>$driver->address];
            $i++ ;
        }
            return response()->json([
            'messege'=> 'Get Driver Nearby Succesfuly ',
            'drivers' => $response

    ]);
}

public function confirmtrip()
{
    $user= Auth::guard('api')->user();
    $trip =Trip::where( 'user_id',$user->id)->latest()->first();
    $driver = Driver::where('id' , $trip->driver_id)->first();
    $server_key = env('FCM_SERVER_KEY');
    $fcm = Http::acceptJson()->withToken($server_key)->post(
        'https://fcm.googleapis.com/fcm/send' ,
          [
            'to' => $driver->fcm_token ,
            'notification' =>
            [
                'title' => 'New Trip' ,
                'body' => $user->name . 'requested a trip from' . $trip->first_location .
                'to' . $trip->end_location . ' , notes :' . $trip->note . ' , number : ' .
                $user->number
            ]
          ]
            );

        return json_decode($fcm);
}

}


