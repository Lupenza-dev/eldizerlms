<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Entities\Gender;
use App\Models\Entities\MaritalStatus;
use App\Models\Entities\Region;
use App\Models\Management\College;
use Illuminate\Http\Request;
use App\Models\Management\Customer;
use App\Models\Management\Student;
use App\Traits\CustomerTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{
    use CustomerTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $requests =$request->all();
        $filter   =Auth::user()->hasRole('Agent') ? true : false;
        $customers =Customer::with('region','district','ward','student','student.college','gender','user')
                    ->whereHas('student',function($query) use ($requests , $filter){
                        $query->withfilters($requests);
                        if ($filter) {
                            $query->where('college_id',getCollegeId());
                        }
                    })
                    ->when($requests,function ($query) use ($requests){
                        $query->withfilters($requests);
                    })
                    ->latest()
                    ->get();
        $regions   =Region::get();
        $colleges  =College::get();
        $roles     =Role::whereNotIn('id',[1,2])->get(['name','id']);
        return view('managements.customers.customers',compact('customers','regions','colleges','requests','roles'));
    }

    public function generateExcelReport(Request $request){
        $requests =$request->all();
        $customers =Customer::with('region','district','ward','student','student.college')
                    ->whereHas('student',function($query) use ($requests){
                        $query->withfilters($requests);
                    })
                    ->when($requests,function ($query) use ($requests){
                        $query->withfilters($requests);
                    })
                    ->latest()
                    ->cursor();
        
        return self::exportCustomerReport($customers);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $customer =Customer::with('region','district','ward','marital_status','student','student.college')
        ->where('uuid',$uuid)
        ->first();
       
        return view('managements.customers.profile',compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {   
        $customer =Customer::with('region','district','ward','marital_status','student','student.college')
        ->where('uuid',$uuid)
        ->first();
        $regions         =Region::get();
        $colleges        =College::get();
        $gender          =Gender::get();
        $maritial_status =MaritalStatus::get();
        return view('managements.customers.edit',compact('customer','gender','maritial_status','regions','colleges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $valid =$this->validate($request,[
            'customer_id' =>'required',
            'first_name'  =>'required',
            'middle_name' =>'required',
            'last_name' =>'required',
            'gender'     =>'required',
            'maritial_status' =>'required',
            'dob'         =>'required',
            'phone' =>'required',
            'email' =>'required',
            'id_number' =>'required',
            'region_id' =>'required',
            'district_id'     =>'required',
            'ward_id'         =>'required',
            'street'         =>'required',
            'resident_since' =>'required',
            'college_id'     =>'required',
            'course'         =>'required',
            'student_reg_id'         =>'required',
            'form_four_index_no'     =>'required',
            'study_year'        =>'required',
            'position'          =>'required',
            'heslb_status'      =>'required',
        ]);

        DB::transaction(function () use ($valid , $request){

       
            $customer =Customer::where('uuid',$valid['customer_id'])->first();
            $customer->first_name  =$valid['first_name'];
            $customer->middle_name =$valid['middle_name'];
            $customer->last_name  =$valid['last_name'];
            $customer->other_name =$request['other_name'];
            $customer->gender_id  =$valid['gender'];
            $customer->marital_status_id  =$valid['maritial_status'];
            $customer->dob        =$valid['dob'];
            $customer->phone_number =$valid['phone'];
            $customer->email      =$valid['email'];
            $customer->id_number  =$valid['id_number'];
            $customer->region_id  =$valid['region_id'];
            $customer->district_id  =$valid['district_id'];
            $customer->ward_id  =$valid['ward_id'];
            $customer->street     =$valid['street'];
            $customer->resident_since  =$valid['resident_since'];
            $customer->save();

            $student =Student::updateorCreate(
                ['customer_id'=>$customer->id
                ],
                [
                    'college_id'          =>$valid['college_id'],
                    'course'              =>$valid['course'],
                    'student_reg_id'      =>$valid['student_reg_id'],
                    'form_four_index_no'  =>$valid['form_four_index_no'],
                    'study_year'          =>$valid['study_year'],
                    'position'            =>$valid['position'],
                    'heslb_status'        =>$valid['heslb_status'],
                
                ]);

                return true;
         });

         return response()->json([
            'success'  =>true,
            'message'  =>"The Action Done Successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
