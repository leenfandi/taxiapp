<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Admin;
use Dotenv\Parser\Value;
use Dotenv\Validator as DotenvValidator;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class CustomAuthController extends BaseController
{
public function register(Request $request)
{
$rules =$this->getRulles();
$validator=validator($request->all(),$rules);
if ($validator->fails())
{
return $this ->sendError('validator error',$validator->errors());
}
$request['password']=Hash::make($request['password']);
$input=$request->all();
$user=Admin::create($input);
$token = $user->createToken('homam')->plainTextToken;
$success['token']=$token;
// $success['token']=$user->createToken('homam')->accessToken;
// $success['token']=$user->name;
return $this->sendResponse( $success,'Admin Register successfully');
}
public function login(Request $request)
{
$validator = validator($request->all(),[
'email'=>'required|email|unique:admins,email',
'password' =>'required',
] );
if ($validator->failed()){
return $this ->sendError('validator error',$validator->errors());
}
$user = Admin::where('email',$request->email) -> first();
if ($user){
if(Hash::check($request->password , $user->password)){
$success['token'] = $user->createToken('homam')->plainTextToken;
return $this->sendResponse( $success,'Admin login successfully');
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
'name'=>'required|unique:admins,name',
'email'=>'required|email|unique:admins,email',
'password'=>'required',
];
}
}
