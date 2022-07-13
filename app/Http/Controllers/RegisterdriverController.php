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
     $driver = Auth::guard('driver-api')->user();
     $driver_id = $driver->id;
    $pin = Pin::create([
      'driver_id' => $driver_id ,
      'code' => $this->generatePIN()
    ]);

   // Notification::send($driver , new SendEmail($pin->code));
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
    public function logout()
    {
        Auth::gurd('driver-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    public function delete( $id){
        $driver = Driver::find($id);
        $result = $driver->delete();
        if($result){
            return response()->json([
                'message'=>' A Driver Deleted Successfully'

            ],201);
         } else{
            return response()->json([
                'message'=>'Driver Not Deleted '

            ],400);
            }
        }
        public function updatePro2(Request $request,$id)
        {

            $input = $request->all();
            $id = $request->id;
            $driver = Driver::find($id);
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
        public function getPro($driver_id)
    {
       $driver = Driver::select('id','name' , 'gender' , 'typeofcar' , 'image' , 'number')->where('id', $driver_id)->first();
       if (!$driver)
       {
        return response()->json(['message' => 'driver not found']);
       }
       if ( is_null($driver->image) )
       {
        $image = 'null';
       }
        else{
            $image = asset($driver->image);}
       return response()->json([
        'id' => $driver->id,
        'name' => $driver->name,
        'gender' => $driver->gender,
        'typeofcar' => $driver->typeofcar,
        'image' => $image,
        'number' => $driver->number,
    ]);
    }
    public function generatePIN()
    {
        $pin = mt_rand(0000, 9999);
        return $pin ;

    }

    public function checkPin(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'code'=>'required|numeric',
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),422);
        }
        $driver = auth()->guard('driver-api')->id();
        $pin = Pin::where('driver_id',$driver)->latest()->first();
        if($pin->code == $request->code)
        {
            return response()->json(['message'=>'you are loged in successfully']);

        }
        else {
            return response()->json(['message'=>'check your PIN']);
        }
    }
}
