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
            'from' => $input['first_location'],
            'to' => $input['end_location'],
            'notes' => $input['note'],
            'trip_id' =>$trip->id
        ];
        event(new NewNotification($data));
         return response()->json([
                'messege'=> 'Trip store seccesfuly ',
                'trip now is' =>$trip,

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
    $trip =Trip::where( 'user_id',$user_id)->first();
    $validator = validator($input, [

        'end_location'=>'string',

    ]);
    if ($validator->fails()) {
        return response()->json(['error'=>$validator->errors()]);
    }


    if($request->exists('end_location')){
    $trip->end_location= $input['end_location'] ;
    }
    $trip->save();
    return response()->json(['trip'=>$trip,'msg'=>'trip update succefully']);
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

