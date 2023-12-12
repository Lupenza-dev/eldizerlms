<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Entities\Region;
use App\Models\Management\College;
use Illuminate\Http\Request;
use App\Models\Management\Customer;
use App\Traits\CustomerTrait;
use Illuminate\Support\Facades\Auth;
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
