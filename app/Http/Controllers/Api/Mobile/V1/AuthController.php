<?php

namespace App\Http\Controllers\Api\Mobile\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Validator;
use Hash;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{


  public function userLogin(Request $request){
   // return Hash::make('admin@123');
        $validator = Validator::make(
            $request->all(), [
                'email'     =>'required',
                'password' =>'required',
            ]
        );

        if ($validator->fails() ) {
            return response()->json(
                [
                    'success' => false,
                    'error_message' => $validator->errors(),
                ], 500
            );
        }

        $user_request =$validator->valid();

        if (Auth::attempt(['email' => $user_request['email'], 'password' => $user_request['password']])) {
            $user = User::find(Auth()->user()->id);
            if ($user->active == 1) { 
                return response()->json([
                    'success'  =>true,
                    'message'  =>$user->name.' Welcome at Chuo Credit Application',
                    'data'     =>new UserResource($user),
                    'token'    =>$user->createToken($user->email)->accessToken,
                ],200);

            } else {
                Auth::logout();
                return response()->json([
                    'success' =>false,
                    'error_message' =>"Your Account Has Been Deactivated , Contact System Admin",
                ],500);
            }
        } else {
            return response()->json([
                'success' =>false,
                'error_message' =>'Username /Email or Password Not Correct',
            ],500);
        }
    }

}