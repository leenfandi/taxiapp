<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Driver;
use App\Models\Admin;
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
    public function register (Request $request)
    {

        $validator =Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|string|email|unique:_drivers',
            'password'=>'required|min:8',
            'gender'=>'required',
            'typeofcar'=>'required',
            'number'=>'required|numeric',
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
        $token=Auth::guard('driver-api')->attempt($credentials);
        return response()->json([
            'message'=>'Register successfully',
            'acces_token'=>$token
        ],201);
    }
public function login(Request $request)
{
$validator = validator($request->all(),[
'email'=>'required|email|unique:_drivers,email',
'password' =>'required',
] );
if ($validator->failed()){
return $this ->sendError('validator error',$validator->errors());
}
$user = Driver::where('email',$request->email) -> first();
if ($user){
if(Hash::check($request->password , $user->password)){
$success['token'] = $user->createToken('homam')->plainTextToken;
return $this->sendResponse( $success,'User login successfully');
} else {
return $this->sendError('Check your input', ['error' => 'Password mismatch']);
}
}
else {

return $this->sendError('Check your input', ['error' => 'User does not exist']);
}
}
public function logout(Request $request)
{
$token = $request->user()->token();
$token->revoke();
return response()->json(['message'=>'thank you for using our app came back later']);
}
public function getRulles()
{
return $rules =[
'name'=>'required|unique:_drivers,name',
'email'=>'required|email|unique:_drivers,email',
'password'=>'required',
'gender'=>'required',
'image'=>'nullable|image',
'typeofcar'=>'required',
'number'=>'required|numeric'
];
}
public function getprofile($driver_id )
    {
        try{

            $driver = Driver::where('id', $driver_id)->first();
           // $rules =$this->getRulles();
           return response()->json($driver);
   // }

    }catch(\Exception $e) {

        return response()->json(['status'=>'false','message'=>$e->getMessage(),'data'=>[]],500);
    }}
    public function updateprofile( Request $request)
    {
try{
    $input = $request->all();

        $validator = validator($input,[
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

        }
        $id = $request->id;
        $driver = Driver::find($id);

           if($request->exists('name')){
            $driver->name= $input['name'];
           }
           if($request->exists('email')){
            $driver->name= $input['email'];
           }
           if($request->exists('gender')){
            $driver->name= $input['gender'];
           }
           if($request->exists('typeofcar')){
            $driver->name= $input['typeofcar'];
           }
           if($request->exists('number')){
            $driver->name= $input['number'];
           }


            if ($request->image && $request->image->isValid()){

            $file_extension = $request->image->extension();
            $file_name = time() . '.' . $file_extension;
            $request->image->move(public_path('images/drivers'), $file_name);
            $path = "public/images/drivers/$file_name";
            $driver->image = $path;
        }

       $driver->save();
        return response()->json(['status'=>'true','message'=>"Profile Updated",'data'=>$driver ]);

        }

    catch(\Exception $e){
    return response()->json(['status'=>'false','message'=>$e->getMessage(),'data'=>[]],500);
   }

}
}
