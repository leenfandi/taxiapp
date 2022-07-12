<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Admin;
use Dotenv\Parser\Value;
use Dotenv\Validator as DotenvValidator;
use Validator;

use Illuminate\Support\Facades\Auth;
class CustomAuthController extends Controller
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
                'email'=>'required|string|email|unique:admins',
                'password'=>'required|min:8',

            ]);

            if ($validator->fails())
            {
                return response()->json($validator->errors()->toJson(),400);
            }
            $user=Admin::create(array_merge(
                $validator->validated(),
                ['password'=>bcrypt($request->password)]
            ));
            $credentials=$request->only(['email','password']);
            $token=Auth::guard('admin-api')->attempt($credentials);
            return response()->json([
                'message'=>'Register successfully',
                'acces_token'=>$token
            ],201);
        }
        /**
         * Login
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

         if(!$token=Auth::guard('admin-api')->attempt($credentials))
         {
           return response()->json(['error'=>'Unauthorized'],401);
         }

         return response()->json([
             'access_token'=>$token,
             'user'=>Auth::guard('admin-api')->user(),

           ]);
        }
        /**
         * Get the authenticated User.
         *
         * @return \Illuminate\Http\JsonResponse
         */
       /* public function me()
        {
            return response()->json(auth()->user());
        }
        /**
         * Log the user out (Invalidate the token).
         *
         * @return \Illuminate\Http\JsonResponse
         */
       public function logout()
        {
           Auth::guard('admin-api')->logout();
            return response()->json(['message'=>'thank you for using our app came back later']);

        }

        /**
         * Refresh a token.
         *
         * @return \Illuminate\Http\JsonResponse
         */
       /* public function refresh()
        {
            return $this->respondWithToken(auth()->refresh());
        }
        /**
         * Get the token array structure.
         *
         * @param  string $token
         *
         * @return \Illuminate\Http\JsonResponse*/

}
