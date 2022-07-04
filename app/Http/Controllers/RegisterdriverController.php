<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Driver;
use App\Models\Admin;
use Dotenv\Parser\Value;
use Dotenv\Validator as DotenvValidator;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class RegisterdriverController extends BaseController
{
public function register(Request $request , $admin_id)
{
    $admin = Admin::where('id', $admin_id)->first();
    $drivers=Driver::with(['admin'])->where('admin_id',$admin_id);
$rules =$this->getRulles();
$validator=validator($request->all(),$rules);
if ($validator->fails())
{
return $this ->sendError('validator error',$validator->errors());
}
$request['password']=Hash::make($request['password']);
$input=$request->all();
$user = new Driver;
$user->name = $request->name;
$user->email = $request->email;
$user->password = $request->password;
$user->gender = $request->gender;
$user->typeofcar = $request->typeofcar;
$user->number = $request->number;
$user->admin_id = $admin_id;
$user->save();
//$user=Driver::create($input);
$token = $user->createToken('homam')->plainTextToken;
$success['token']=$token;
// $success['token']=$user->createToken('homam')->accessToken;
// $success['token']=$user->name;
return $this->sendResponse( $success,'User Register successfully');
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
    //$driver->save();
    return response()->json(['status'=>'false','message'=>$error,'data'=>[]],422);
    if( $request->driver_id != Auth::id())
   {
       return $this->sendError('you dont have rights', $errormessage);
    }
        }else{

           // $driver_id = Driver::where('id')->first();

           // $driver = Driver::where('driver_id',$driver_id);
          // $driver = Driver::where('id')->first();
            //if($driver){
            $driver = new Driver();
            $driver->name = $request->name;
            $driver->email = $request->email;
            $driver->gender = $request->gender;
            $driver->typeofcar = $request->typeofcar;
            $driver->number = $request->number;
            $driver->password=$request->password;
        // $driver->save();

            if ($request->image && $request->image->isValid()){

            $file_extension = $request->image->extension();
            $file_name = time() . '.' . $file_extension;
            $request->image->move(public_path('images/drivers'), $file_name);
            $path = "public/images/drivers/$file_name";
            $driver->image = $path;
        }


        return response()->json(['status'=>'true','message'=>"Profile Updated",'data'=>$driver ]);
//$driver->update();
        //$driver = save();
        }
      //  $driver->update();
   } catch(\Exception $e){
    return response()->json(['status'=>'false','message'=>$e->getMessage(),'data'=>[]],500);
   }

}
}
