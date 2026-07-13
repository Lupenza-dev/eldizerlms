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
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use CustomerTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $requests =$request->all();
        $regions   =Region::get();
        $colleges  =College::get();
        $roles     =Role::whereNotIn('id',[1,2])->get(['name','id']);
        return view('managements.customers.customers',compact('regions','colleges','requests','roles'));
    }

    public function getCustomersData(Request $request)
    {
        $requests =$request->all();
        $is_admin =Auth::user()->hasRole(['Admin','Super Admin']);

        $customers = Customer::with('region', 'district', 'ward', 'student', 'student.college', 'gender', 'user.roles')
            ->where(function ($query) use ($requests) {
                $query->whereDoesntHave('student')
                    ->orWhereHas('student', function ($subQuery) use ($requests) {
                        $subQuery->withfilters($requests);
                    });
            })
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->latest();

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('reg_date', function ($customer) {
                return date('d M Y', strtotime($customer->created_at));
            })
            ->addColumn('customer_info', function ($customer) {
                return '<div class="flex flex-col gap-0.5">
                            <span class="text-sm font-semibold text-slate-800">'.e($customer->customer_name).'</span>
                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                <i class="bx bx-phone text-cyan-500"></i>'.e($customer->phone_number).'</span>
                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                <i class="bx bx-envelope text-cyan-500"></i>'.e($customer->email).'</span>
                        </div>';
            })
            ->addColumn('gender', function ($customer) {
                if ($customer->gender?->name == 'Male') {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200"><i class="bx bx-male mr-1"></i> Male</span>';
                }
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-700 border border-pink-200"><i class="bx bx-female mr-1"></i> '.e($customer->gender?->name ?? 'N/A').'</span>';
            })
            ->addColumn('id_number', function ($customer) {
                return '<span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">'.e($customer->id_number).'</span>';
            })
            ->addColumn('address', function ($customer) {
                return $customer->address;
            })
            ->addColumn('customer_type', function ($customer) {
                return $customer->customer_reg_type;
            })
            ->addColumn('roles', function ($customer) use ($is_admin) {
                if (!$is_admin) {
                    return '';
                }
                $html ='';
                foreach ($customer->user?->roles ?? [] as $role) {
                    $badge = strtolower($role->name) == 'admin' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-cyan-100 text-cyan-700 border border-cyan-200';
                    $html .= '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mr-1 '.$badge.'">'.e($role->name).'</span>';
                }
                return $html;
            })
            ->addColumn('actions', function ($customer) use ($is_admin) {
                if (!$is_admin) {
                    return '';
                }
                return '<div class="btn-group">
                            <button type="button" class="inline-flex items-center gap-1 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium px-3 py-1.5 rounded-l-lg transition-colors">Actions</button>
                            <button type="button" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1.5 rounded-r-lg border-l border-slate-600 dropdown-toggle dropdown-toggle-split transition-colors" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-xl overflow-hidden">
                                <li>
                                    <a class="dropdown-item flex items-center gap-2 py-2 text-sm role-btn" data-bs-toggle="modal" data-bs-target="#roleModel" data-id="'.$customer->id.'" data-name="'.e($customer->customer_name).'" data-email="'.e($customer->email).'">
                                        <i class="bx bx-user-voice text-cyan-500"></i> Manage Roles
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="'.route('customers.show', $customer->uuid).'">
                                        <i class="bx bx-user text-blue-500"></i> View Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="'.route('customers.edit', $customer->uuid).'">
                                        <i class="bx bx-edit text-emerald-500"></i> Edit Customer
                                    </a>
                                </li>
                            </ul>
                        </div>';
            })
            ->filterColumn('customer_info', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('phone_number', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('id_number', function ($query, $keyword) {
                $query->where('id_number', 'like', "%{$keyword}%");
            })
            ->rawColumns(['customer_info', 'gender', 'id_number', 'address', 'roles', 'actions'])
            ->make(true);
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
        $customer =Customer::with('region','district','ward','marital_status','student','student.college','intern')
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
