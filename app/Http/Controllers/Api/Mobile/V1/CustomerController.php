<?php 

namespace App\Http\Controllers\Api\Mobile\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerStoreRequest;
use App\Models\Management\Customer;
use App\Models\Management\Student;
use App\Models\User;
use Hash;
use Str;
use DB;
use Storage;
use Auth;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\StudentResource;
use App\Http\Requests\StudentStoreRequest;
use Log;

class CustomerController extends Controller
{
    public function store(CustomerStoreRequest $request){
         $valid_data =$request->validated();
         $other_name =$request->other_name;
         $customer_obj =null;

         Log::debug($request->all());

        try {
            DB::transaction(function() use ($valid_data,$other_name,&$customer_obj,$request){

            $customer_obj =Customer::StoreCustomer($valid_data,$other_name);
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
            $customer_obj->save();

            $user =User::create([
                'name'  =>ucwords($valid_data['first_name'].' '.$valid_data['middle_name'].' '.$valid_data['last_name']),
                'email' =>$valid_data['email'],
                'phone_number' =>$valid_data['phone_number'],
                'password'     =>Hash::make(123456),
                'uuid'         =>(string)Str::orderedUuid(),
                'customer_id'  =>$customer_obj->id,
            ]);
            Log::debug('step 2');
    
            $user->assignRole('Customer');

            $student =Student::create([
                'customer_id'  =>$customer_obj->id,
                'college_id'   =>$valid_data['college_id'],
               'form_four_index_no' =>$valid_data['index_no'],
                'study_year'      =>$valid_data['study_year'],
                'student_reg_id'  =>$valid_data['student_reg_id'],
                'heslb_status'    =>$valid_data['heslb_status'],
                'course'          =>$valid_data['course'],
                'uuid'            =>(string)Str::orderedUuid(),
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

    public function storeStudent(StudentStoreRequest $request){
        $valid_data =$request->validated();
        
        $student =Student::create([
            'customer_id'  =>Auth::user()->customer_id ?? 3,
            'college_id'   =>$valid_data['college_id'],
            'position'     =>$valid_data['position'],
            'study_year'   =>$valid_data['study_year'],
            'student_reg_id'  =>$valid_data['student_reg_id'],
            'heslb_status'    =>$valid_data['heslb_status'],
            'course'          =>$valid_data['course'],
            'uuid'            =>(string)Str::orderedUuid(),
        ]);

        return response()->json([
            'success' =>true,
            'message' =>'Registration Done Success Fully',
            'data'    =>new StudentResource($student),
           ],200
        );
    }
}
