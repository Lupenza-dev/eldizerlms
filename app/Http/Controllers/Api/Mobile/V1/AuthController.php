<?php

namespace App\Http\Controllers\Api\Mobile\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Validator;
use Hash;
use App\Http\Resources\UserResource;
use Carbon\Carbon;

class AuthController extends Controller
{


  public function userLogin(Request $request){
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
                if ($request->expo_push_token ?? null) {
                    $user->device_token =$request->expo_push_token;
                    $user->save();
                }

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

    public function changePassword(Request $request){
        $validator = Validator::make(
            $request->all(),
             [
                'old_password' =>'required',
                'password'     =>['required','confirmed','string','min:6','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/','regex:/[@$!%*#?&]/',
                ],
            ]
        );

        if ($validator->fails() ) {
            return response()->json(
                [
                    'success' => false,
                    'error_message' => "Password Must Contain Special Character and number",
                ], 500
            );
        }

        $valid =$validator->valid();

        $user =Auth::user();
        if (!Hash::check($valid['old_password'],$user->password)) {
            return response()->json([
                'success'        =>false,
                'error_message'  =>"Old Password is not correct",
            ],500);
        }

        $user->password =Hash::make($valid['password']);
        $user->is_password_changed =true;
        $user->password_change_date =Carbon::now();
        $user->save();
        
        return response()->json([
            'success' =>true,
            'message' =>'Password Change Successfully',
        ],200);
    }

}