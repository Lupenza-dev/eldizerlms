<?php

namespace App\Http\Controllers\Loan;

use App\Event\LoanApplied;
use App\Http\Controllers\Controller;
use App\Models\Entities\Region;
use Illuminate\Http\Request;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanContract;
use App\Models\Loan\Installment;
use App\Models\Loan\LoanApproval;
use App\Models\Management\College;
use App\Models\Payment\DisbursmentPayment;
use Auth;
use DB;
use Str;
use App\Traits\LoanTrait;
use Carbon\Carbon;

class LoanApplicationController extends Controller
{
    use LoanTrait;

    public function index(Request $request){
        $regions   =Region::get();
        $colleges  =College::get();
        $requests  =$request->all();
        $filter   =Auth::user()->hasRole('Agent') ? true : false;
        $loans =LoanApplication::with('customer','customer.student')
                ->where('level','!=','Canceled')
                ->where('level','!=','GRANTED')
                ->where('level','!=','CLOSED')
                ->whereHas('customer',function($query) use ($requests){
                     $query->withfilters($requests);
                })
                ->whereHas('customer.student',function($query) use ($requests , $filter){
                     $query->withfilters($requests); 
                     if ($filter) {
                        $query->where('college_id',getCollegeId());
                    }
                })
                ->when($requests, function($query) use ($requests){
                    $query->withfilters($requests);
                })
                ->latest()
                ->get();
        return view('loans.loan_applications',compact('loans','requests','regions','colleges'));
    }

    public function generateExcelReport(Request $request){
        $requests  =$request->all();
        $loans =LoanApplication::with('customer','customer.student','college','customer.region','customer.district','customer.ward')
                ->where('level','!=','Canceled')
                ->where('level','!=','GRANTED')
                ->where('level','!=','CLOSED')
                ->whereHas('customer',function($query) use ($requests){
                     $query->withfilters($requests);
                })
                ->whereHas('customer.student',function($query) use ($requests){
                     $query->withfilters($requests);
                })
                ->when($requests, function($query) use ($requests){
                    $query->withfilters($requests);
                })
                ->cursor();

        return self::exportLoanApplicationReport($loans);

         
    }

    public function profile($uuid){
        $loan =LoanApplication::with('customer','guarantors')->where('uuid',$uuid)->first();
        return view('loans.loan_application_profile',compact('loan'));
    }

    public function rejectApplication(Request $request){
        $loan_uuid  =$request->loan_uuid;
        $remark     =$request->remark;

        $status   =Auth::user()->hasRole('Agent') ? "Rejected by Agent" : "Rejected by Admin";

        $loan =LoanApplication::where('uuid',$loan_uuid)->first();
        $loan->remark =$remark;
        $loan->level  =$status;
        $loan->attended_by =Auth::user()->id;
        $loan->save();

        $loan_approval =LoanApproval::where('loan_application_id',$loan->id)->first();
        if ($loan_approval) {
            $loan_approval->status =$status;
            $loan_approval->remark =$remark;
            $loan_approval->attended_date =Carbon::now();
            $loan_approval->save();
        }

        event (new LoanApplied($loan,3));

        return response()->json([
            'success'  =>true,
            'message'  =>"The Action Done Successfully",
        ]);
    }

    public function agentApproveApplication(Request $request){
        $loan_uuid  =$request->loan_uuid;
        $remark     =$request->remark;

        $status   ="Approved by Agent";

        $loan =LoanApplication::where('uuid',$loan_uuid)->first();
        $loan->remark =$remark;
        $loan->level  =$status;
        $loan->attended_by =Auth::user()->id;
        $loan->save();

        $loan_approval =LoanApproval::where('loan_application_id',$loan->id)->first();
        if ($loan_approval) {
            $loan_approval->status =$status;
            $loan_approval->remark =$remark;
            $loan_approval->attended_date =Carbon::now();
            $loan_approval->save();
        }

        event (new LoanApplied($loan,2));


        return response()->json([
            'success'  =>true,
            'message'  =>"The Action Done Successfully",
        ]);
    }

    public function approveApplication(Request $request){
        $valid =$this->validate($request,[
            'payment_date' =>'required',
            'payment_reference' =>['required','unique:disbursment_payments,payment_reference'],
            'paid_amount'       =>'required',
            'payment_channel'   =>'required',
            'payment_method'    =>'required',
            'loan_uuid'         =>'required',
        ]);

        $loan =LoanApplication::where('uuid',$valid['loan_uuid'])->where('level','!=','GRANTED')->first();

        if (!$loan) {
            return response()->json([
                'success' =>false,
                'errors'  =>'Customer Doesnot Has Active Loan Application',
            ],500);
        }

        if ($valid['paid_amount'] < $loan->amount) {
            return response()->json([
                'success' =>false,
                'errors'  =>'Disbursed Amount is Less Than applied Amount',
            ],500);
        }

        $outstanding_loan =$loan->customer->outstanding_loan;

        if ($outstanding_loan) {
            return response()->json([
                'success' =>false,
                'errors'  =>'Customer Has Outstanding Loan',
            ],500);
        }

          try {
            DB::transaction(function() use ($valid){
               $loan =LoanApplication::where('uuid',$valid['loan_uuid'])->first();
               $loan_contract =LoanContract::create([
                'customer_id' =>$loan->customer_id,
                'loan_application_id'      =>$loan->id,
                'college_id'               =>$loan->college_id,
                'amount'                   =>$loan->amount,
                'loan_amount'              =>$loan->loan_amount,
                'installment_amount'       =>$loan->installment_amount,
                'plan'       =>$loan->plan,
                'status'       =>"GRANTED",
                'current_balance'           =>0,
                'outstanding_amount'        =>$loan->loan_amount,
                'contract_code'             =>$loan->loan_code,
                'interest_rate'             =>$loan->interest_rate,
                'interest_amount'           =>$loan->interest_amount,
                'fees_amount'               =>$loan->fees_amount,
                'start_date'                =>$valid['payment_date'],
                'expected_end_date'         =>date('Y-m-d', strtotime("+".$loan->plan." months", strtotime($valid['payment_date']))),
                'created_by'                =>Auth::user()->id,
                'uuid'                      =>(string)Str::orderedUuid(),
                'other_fees'                 =>$loan->other_fees
               ]);

              // for ($i=1; $i < $loan->plan; $i++) {
                $i =1;
                $installment =Installment::create([
                    'loan_contract_id' =>$loan_contract->id,
                    'installment_order' =>$i,
                    'total_amount'      =>$loan_contract->installment_amount,
                    'current_balance'   =>0,
                    'outstanding_amount' =>$loan_contract->installment_amount,
                    'payment_type'       =>"Installment Amount",
                    'payment_date'       =>date('Y-m-d', strtotime("+".$i." months", strtotime($loan_contract->start_date))),
                    'last_paid_amount'   =>0,
                    'status'             =>"OPEN",
                    'uuid'               =>(string)Str::orderedUuid(),
                ]);
             // }

               $payment =DisbursmentPayment::create([
                'loan_contract_id'    =>$loan_contract->id,
                'loan_application_id' =>$loan->id,
                'payment_reference'   =>$valid['payment_reference'],
                'paid_amount'         =>$valid['paid_amount'],
                'payment_date'      =>$valid['payment_date'],
                'payment_channel'   =>$valid['payment_channel'],
                'payment_method'    =>$valid['payment_method'],
                'created_by'        =>Auth::user()->id,
                'uuid'              =>(string)Str::orderedUuid(),
               ]);
            });

            $loan->level ="GRANTED";
            $loan->save();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }
}
