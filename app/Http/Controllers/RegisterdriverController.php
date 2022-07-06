<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Driver;

use Dotenv\Parser\Value;
use Dotenv\Validator as DotenvValidator;
use Validator;

use Illuminate\Support\Facades\Auth;
class RegisterdriverController extends Controller
{

    protected $db_mysql;
    public function __construct()
    {
        $this ->db_mysql= config('database.connections.mysql.database');

    }
    /**
     * Register
     */

    public function login(Request $request)
    {
     $validator =Validator::make($request->all(),[

         'email'=>'required|string|email',
         'password'=>'required|string|min:8',
     ]);
     if ($validator->fails())
     {
         return response()->json($validator->errors()->toJson(),422);
     }
     $credentials=$request->only(['email','password']);

     if(!$token=auth()->guard('driver-api')->attempt($credentials))
     {
       return response()->json(['error'=>'Unauthorized'],401);
     }

     return response()->json([
         'access_token'=>$token,
         'user'=>auth()->guard('driver-api')->user(),

       ]);

    }

    public function updatePro(Request $request)
    {

        $input = $request->all();
        $id = $request->id;
        $driver = Driver::where($id)->first();
        $validator = validator($input, [
            'name'=>'string',
            'email'=>'string|email|unique:_drivers',
            'password'=>'min:8',
            'image'=>'nullable|image',
            'gender'=>'string',
            'typeofcar'=>'string',
            'number'=>'numeric',



        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()]);
        }

        if($request->exists('name')){
        $driver->name= $input['name'] ;
        }
        if($request->exists('email')){
        $driver->email= $input['email'] ;
        }
        if($request->exists('password')){
        $driver->password=  Hash::make($input['password'])  ;
        }
        if($request->exists('gender')){
        $driver->gender=  $input['gender'] ;
        }
        if($request->exists('typeofcar')){
            $driver->typeofcar= $input['typeofcar'] ;
        }
        if($request->exists('number')){
            $driver->number= $input['number'] ;
        }
        if ($request->image && $request->image->isValid()){

            $file_extension = $request->image->extension();
            $file_name = time() . '.' . $file_extension;
            $request->image->move(public_path('images/drivers'), $file_name);
            $path = "public/images/drivers/$file_name";
            $driver->image = $path;
        }

        $driver->save();
        return response()->json(['driver'=>$driver,'msg'=>'driver update succefully']);
    }

}
