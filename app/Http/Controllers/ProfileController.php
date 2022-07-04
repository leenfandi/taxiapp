<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use Validator;
use App\Models\Driver;
//use App\Http\Resources\Taxi as TaxiResources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   /* public function getprofile( $driver_id)
    {
        try{


        $driver = Driver::where('id', $driver_id)->first();

     if($driver) {
          $data = Profile::with(['driver'])->where('driver_id',$driver_id)->get();
           return response()->json(['status'=>'true','message'=>"driver Profile",'data'=>$data]);
    }

    }catch(\Exception $e) {

        return response()->json(['status'=>'false','message'=>$e->getMessage(),'data'=>[]],500);
    }

        }*/

        /*public function store(Request $request)
        {
            try{
             //   $driver_id = Auth::guard('driver');

        $driver_id = Driver::where('id')->first();
            //if($driver_id){
            $driver = Profile::with(['driver'])->where('driver_id',$driver_id);
           if ($driver) {
            $input=$request->all();
            $validator = Validator::make($input, [
                'name'=> 'required|min:2|max:100',
                'email'=>'required|email',
                'gender'=>'required',
                'image'=>'nullable|image',
                'typeofcar'=>'required',
                'number'=>'required|numeric'
               ]);
            }
            if ($validator->fails())
            {
        $error = $validator->errors()->all()[0];
        return response()->json(['status'=>'false','message'=>$error,'data'=>[]],422);
            }else{

            $profile = Profile::create(request([
                $driver->name = $request->name,
            $driver->email = $request->email,
            $driver->gender = $request->gender,
            $driver->typeofcar = $request->typeofcar,
            $driver->number = $request->number,
           // $driver->driver_id = $driver_id,
            ]));
            if ($request->image && $request->image->isValid()){

                $file_extension = $request->image->extension();
                $file_name = time() . '.' . $file_extension;
                $request->image->move(public_path('images/drivers'), $file_name);
                $path = "public/images/drivers/$file_name";
                $driver->image = $path;
            }
            $pro = $driver->save();

            return $this->sendResponse($driver,'profile store successfully');
        }}
        catch(\Exception $e){
            return response()->json(['status'=>'false','message'=>$e->getMessage(),'data'=>[]],500);
           }

        }*/








/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  /*  public function updateprofile( Request $request)
    {
try{
        $validator = Validator::make($request->all(),[
         'name'=> 'required|min:2|max:100',
         'email'=>'required|email',
         'gender'=>'required',
         'image'=>'nullable|image',
         'typeofcar'=>'required',
         'number'=>'required|numeric'
        ]);
        if ($validator->fails())
        {
    $error = $validator->errors()->all()[0];
    return response()->json(['status'=>'false','message'=>$error,'data'=>[]],422);
    if( $request->driver_id != Auth::id())
    {
        return $this->sendError('you dont have rights', $errormessage);
    }
        }else{

           // $driver_id = Driver::where('id')->first();

           // $driver = Profile::with(['driver'])->where('driver_id',$driver_id);
          // $driver = Driver::where('id')->first();
            //if($driver){
            $driver = new Driver;
            $driver->name = $request->name;
            $driver->email = $request->email;
            $driver->gender = $request->gender;
            $driver->typeofcar = $request->typeofcar;
            $driver->number = $request->number;
          //  $driver->update();

            if ($request->image && $request->image->isValid()){

            $file_extension = $request->image->extension();
            $file_name = time() . '.' . $file_extension;
            $request->image->move(public_path('images/drivers'), $file_name);
            $path = "public/images/drivers/$file_name";
            $driver->image = $path;
        }
    //}
        //$makes= Profile::make($validator);
        return response()->json(['status'=>'true','message'=>"Profile Updated",'data'=>$driver ]);
//$driver->update();
        $driver = save();
        }
      //  $driver->update();
   } catch(\Exception $e){
    return response()->json(['status'=>'false','message'=>$e->getMessage(),'data'=>[]],500);
   }

}
*/

}
