<?php

namespace App\Http\Controllers;
use App\models\Admin;
Use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class AdminAddedController extends Controller
{
    protected $db_mysql;
    public function __construct()
    {
        $this ->db_mysql= config('database.connections.mysql.database');

    }
    public function addDriver( Request $request)
    {
        $validator =Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|string|email|unique:_drivers',
            'password'=>'required|min:8',
            'image'=>'nullable',
            'gender'=>'required',
            'typeofcar'=>'required',
            'number'=>'required|numeric',
           // 'admin_id'=>'nullable',

        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }
        $user=Driver::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password)]
        ));
        $credentials=$request->only(['email','password']);
        $token=auth()->guard('driver-api')->attempt($credentials);
        return response()->json([
            'message'=>'driver added successfully',

        ],201);
    }
}
