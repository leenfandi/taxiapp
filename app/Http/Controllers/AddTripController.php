<?php

namespace App\Http\Controllers;
use App\Events\NewNotification;
use App\Models\Driver;
use App\Models\User;
use App\Models\Trip;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AddTripController extends Controller
{
    public function store( Request $request){
        $input = $request->all();
        $trip = new Trip();

        $user_id= Auth::guard('api')->user()->id;
        $trip->user_id = $user_id;
        $trip->start_time= $input['start_time'] ;
         $trip->end_time= $input['end_time'];
         $trip->first_location= $input['first_location'] ;
         $trip->end_location= $input['end_location'] ;
         $trip->note= $input['note'] ;
         $trip->driver_id = $input['driver_id'];

          $trip->save();

          $data=[
            'username' => Auth::guard('api')->user()->name,
            'number' => Auth::guard('api')->user()->number,
            'from' => $input['first_location'],
            'to' => $input['end_location'],
            'notes' => $input['note'],
            'trip_id' =>$trip->id
        ];
        event(new NewNotification($data));

        $Radius = 6371 ;
    $deglat = deg2rad($request->lat2 - $request->lat1);
    $deglong = deg2rad($request->long2 - $request->long1);
    $a = sin($deglat/2) * sin($deglat/2) + cos(deg2rad($request->lat1))
    * cos(deg2rad($request->lat2)) * sin($deglong/2) * sin($deglong/2);
    $c = 2 *atan2(sqrt($a),sqrt(1-$a));
    $dist = $Radius * $c ;

    $price = $dist * 6300;

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
public function updatetrip ( Request $request){
     $input = $request->all();
    $user_id= Auth::guard('api')->user()->id;
    $trip =Trip::where( 'user_id',$user_id)->latest()->first();
    $validator = validator($input, [

        'end_location'=>'string',
        'lat1' => 'numeric' ,
        'long1' => 'numeric' ,
        'lat2' => 'numeric' ,
        'long2' => 'numeric'

    ]);
    if ($validator->fails()) {
        return response()->json(['error'=>$validator->errors()]);
    }


    if($request->exists('end_location')){
    $trip->end_location= $input['end_location'] ;
    }
    $trip->save();

    $Radius = 6371 ;
    $deglat = deg2rad($request->lat2 - $request->lat1);
    $deglong = deg2rad($request->long2 - $request->long1);
    $a = sin($deglat/2) * sin($deglat/2) + cos(deg2rad($request->lat1))
    * cos(deg2rad($request->lat2)) * sin($deglong/2) * sin($deglong/2);
    $c = 2 *atan2(sqrt($a),sqrt(1-$a));
    $dist = $Radius * $c ;

    $price = $dist * 6300;

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
}

