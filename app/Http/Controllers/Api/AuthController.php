<?php

namespace App\Http\Controllers\Api;

use App\User;
use Carbon\Carbon;
use App\Member;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


class AuthController extends Controller
{
   


    public function postLogin(Request $request)
    {
        if(!$request->email or !$request->password)
        {
            return response()->json(array(
                                    'error'=>true,
                                    'message'=>'Email or Password is required'
                                    ),401);
        }

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication passed...
            $user = User::where('email', $request->email)->with('member')->first();
            
            if(!$user)
            {
                return response()->json(array(
                                    'status_code'=>404,
                                    'error'=>true,
                                    'message'=>'User not found'
                                    ),404);
            }

            $user = array(
                            'id'=>$user->id,
                            'name'=>$user->member->name,
                            'avatar_path'=>secure_asset($user->member->avatar_path),
                        );
            // all good so return the user
            return response()->json(array(
                                    'user'=>$user,
                                    'status_code'=>200,
                                    'error'=>false,
                                    'message'=>'Success to login'
                                    ),200);
        }
        else
        {
            return response()->json(array(
                                    'status_code'=>404,
                                    'error'=>true,
                                    'message'=>'User not found'
                                    ),404);
        }
        
    }
    

}
