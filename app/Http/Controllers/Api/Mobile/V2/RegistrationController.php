<?php

namespace App\Http\Controllers\Api\Mobile\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Management\Customer;
use App\Models\Management\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Hash;
use Str;
use Log;
use Auth;

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

    public function registerUserAddress(Request $request){
        $validator = Validator::make(
            $request->all(), [
                'id_number'      =>'required',
                'region_id'      =>'required',
                'district_id'    =>'required',
                'ward_id'        =>'required',
                'street'         =>'required',
                'resident_since' =>'required',
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
        $other_name =$request->other_name;
        $customer_obj =null;
        try {
            DB::transaction(function() use ($valid,$other_name,$customer_obj){
            
            $customer_obj =Customer::where('id',Auth::user()->customer_id)->update([
                'id_number'         =>$valid['id_number'],
                'region_id'         =>$valid['region_id'],
                'district_id'       =>$valid['district_id'],
                'ward_id'           =>$valid['ward_id'],
                'street'            =>$valid['street'],
                'resident_since'    =>$valid['resident_since'],
                'other_name'        =>$other_name ?? null,
                'registration_stage' =>2,
            ]);
           
            });
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $e->getMessage();
        }
        return response()->json([
            'success' =>true,
            'message' =>'Registration Done Success Fully',
            'data'    =>new CustomerResource($customer_obj),
           ],200
        );
    }

    public function registerUserCollege(Request $request){
        $validator = Validator::make(
            $request->all(), [
                'college_id'      =>'required',
                'form_four_index_no'      =>'required',
                'study_year'    =>'required',
                'student_reg_id'        =>'required',
                'heslb_status'         =>'required',
                'course' =>'required',
                'course' =>'required',
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
        
        $valid_data = $validator->valid();
        $student_obj =null;
        try {
            DB::transaction(function() use ($valid_data,$student_obj){
            
            $student_obj =Student::updateOrCreate([
                'customer_id'  =>Auth::user()->customer_id],
                [
                'college_id'   =>$valid_data['college_id'],
               'form_four_index_no' =>$valid_data['index_no'],
                'study_year'      =>$valid_data['study_year'],
                'student_reg_id'  =>$valid_data['student_reg_id'],
                'heslb_status'    =>$valid_data['heslb_status'],
                'course'          =>$valid_data['course'],
                'uuid'            =>(string)Str::orderedUuid(),
            ]);

            $customer_obj =Customer::where('id',Auth::user()->customer_id)->update([
                'registration_stage' =>3,
            ]);
           
            });
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return $e->getMessage();
        }
        return response()->json([
            'success' =>true,
            'message' =>'Registration Done Success Fully',
            'data'    =>new CustomerResource($student_obj->customer),
           ],200
        );
    }

    public function registerUserImage(Request $request){
        $validator = Validator::make(
            $request->all(), [
                'image'      =>'required',
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

        $customer_obj =Customer::where('id',Auth::user()->customer_id)->first();
        Log::debug('step 1');
        if ($request->hasfile('image')) {
            Log::debug('step 2');
            $file = $request->file('image');
            $filename =time().$file->getClientOriginalName();
            $path     =Storage::disk('local')->putFileAs('',$file,$filename);

            $imageName = $request->file('image')->store('images', 'public');
        }
        Log::debug($imageName);
        $customer_obj->image =$imageName ?? null;
        $customer_obj->registration_stage =4;
        $customer_obj->save();

        return response()->json([
            'success' =>true,
            'message' =>'Registration Done Success Fully',
            'data'    =>new CustomerResource($customer_obj),
           ],200
        );
        
    }
}
