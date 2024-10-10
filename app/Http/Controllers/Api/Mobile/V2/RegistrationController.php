<?php

namespace App\Http\Controllers\Api\Mobile\V2;

use App\Http\Controllers\Controller;
use App\Models\Management\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Hash;
use Str;
use Log;

class RegistrationController extends Controller
{
    public function registerUser(Request $request){
        $validator = Validator::make(
            $request->all(), [
                'first_name'       =>'required',
                'middle_name'      =>'required',
                'last_name'        =>'required',
                'phone_number'     =>'required',
                'password'        =>'required',
                'email'           => ['required','unique:users,email']
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
        
        $valid = $validator->valid();
        $expo_push_token =$request->expo_push_token;
        try {
            DB::transaction(function() use ($valid,$expo_push_token){
            $customer_obj =Customer::create([
                'first_name'   =>$valid['first_name'],
                'middle_name'  =>$valid['middle_name'],
                'last_name'    =>$valid['last_name'],
                'phone_number' =>'255'.''.$valid['phone_number'],
                'email'        =>$valid['email'],
                'registration_stage' =>1,
                'uuid'               =>(string)Str::orderedUuid()
            ]);

            $user =User::create([
                'name'  =>ucwords($valid['first_name'].' '.$valid['middle_name'].' '.$valid['last_name']),
                'email' =>$valid['email'],
                'phone_number' =>'255'.''.$valid['phone_number'],
                'password'     =>Hash::make($valid['password']),
                'uuid'         =>(string)Str::orderedUuid(),
                'device_token' =>$expo_push_token,
                'customer_id'  =>$customer_obj->id,
            ]);
    
            $user->assignRole('Customer');
            });
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $e->getMessage();
        }


        return response()->json([
            'success' =>true,
            'message' =>'Registration Done Success Fully',
           ],200
        );
       



    }
}
