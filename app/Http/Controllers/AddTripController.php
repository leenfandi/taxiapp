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
}

