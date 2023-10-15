<?php

namespace App\Http\Controllers\Api\Mobile\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entities\Region;
use App\Models\Entities\District;
use App\Models\Entities\Ward;
use App\Models\Management\College;
use App\Models\Management\Student;
use Auth;
use App\Http\Resources\AgentResource;
use App\Models\Loan\LoanApplication;
use App\Http\Resources\LoanApplicationResource;
use App\Http\Resources\PaymentResource;
use App\Models\Payment\Payment;

class HomeController extends Controller
{
    public function getRegions(){
        $regions =Region::orderBy('name','ASC')->get(['id','name']);
        return response()->json([
            'success' =>true,
            'data'    =>$regions
        ]);
    }

    public function getDistricts($region_id){
        $districts =District::where('region_id',$region_id)->orderBy('name','ASC')->get(['id','name']);
        return response()->json([
            'success' =>true,
            'data'    =>$districts,
        ]);
    }

    public function getWards($district_id){
        $wards =Ward::where('district_id',$district_id)->orderBy('name','ASC')->get(['id','name']);
        return response()->json([
            'success' =>true,
            'data'    =>$wards,
        ]);
    }

    public function getColleges(){
        $colleges =College::orderBy('name','ASC')->get(['id','name','logo']);
        return response()->json([
            'success' =>true,
            'data'    =>$colleges
        ]); 
    }

    public function getAgents(){
        $student =Student::where('customer_id',Auth::user()->customer_id)->first();

        return response()->json([
            'success' =>true,
            'data'    =>AgentResource::collection($student->agents ?? []),
        ]);
    }

    public function getLoans(){
        $loans =LoanApplication::where('customer_id',Auth::user()->customer_id ?? 3)->where('level','!=','Canceled')->latest()->get();
        return response()->json([
            'success' =>true,
            'data'    =>LoanApplicationResource::collection($loans),
        ]);
    }

    public function getPayments(){
        $payments =Payment::where('customer_id',Auth::user()->customer_id ?? 3)->latest()->get();
        return response()->json([
            'success' =>true,
            'data'    =>PaymentResource::collection($payments),
        ]); 
    }
}
