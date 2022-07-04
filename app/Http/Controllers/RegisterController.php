<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Dotenv\Parser\Value;
use Dotenv\Validator as DotenvValidator;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class RegisterController extends BaseController
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
$user=User::create($input);
$token = $user->createToken('homam')->plainTextToken;
$success['token']=$token;
// $success['token']=$user->createToken('homam')->accessToken;
// $success['token']=$user->name;
return $this->sendResponse( $success,'User Register successfully');
}
public function login(Request $request)
{
$validator = validator($request->all(),[
'email'=>'required|email|unique:users,email',
'password' =>'required',
] );
if ($validator->failed()){
return $this ->sendError('validator error',$validator->errors());
}
$user = User::where('email',$request->email) -> first();
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
'name'=>'required|unique:users,name',
'email'=>'required|email|unique:users,email',
'password'=>'required',
];
}
}
