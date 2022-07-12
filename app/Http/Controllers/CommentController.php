<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Driver;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function list($driver_id)
    {
        $driver = Driver::where('id', $driver_id)->first();
        if ($driver) {
            $comments=Comment::with(['user'])->where('driver_id',$driver_id)
            ->orderBy('id','desc')->paginate(5);


                return response()->json([
                    'message'=>'comment get',
                    'data'=>$comments,

                ],200);



        }
        else{
            return response()->json([
                'message'=>'comment not found',

            ]);
        }

    }
    public function store($driver_id, Request $request)
    {
        $driver = Driver::where('id', $driver_id)->first();
        if ($driver) {
            $validator = validator($request->all(), [

                'message' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $comment = Comment::create([
            'message' => $request->message,
            'driver_id' => $driver->id,
            'user_id' => $request->user()->id,
            //$addresses->customer_id = $customer_id;
        ]);
        $comment->load('user');

        return response()->json([
            'message'=>'comment added',
            'name'=>$comment->user->name,
            'data'=>$comment['message'],

        ],200);


    }
}
