<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment\DisbursmentPayment;
use App\Models\Payment\Payment;
use App\Models\Loan\LoanContract;
use App\Services\Loan\InstallmentService;
use Auth;
use Str;


class PaymentController extends Controller
{
    public function disbursments(Request $request){
        $payments =DisbursmentPayment::with('loan_contract','loan_contract.customer')->latest()->get();
        return view('payments.disbursments',compact('payments'));
    }

    public function payments(){
        $payments =Payment::with('loan_contract','loan_contract.customer')->latest()->get();
        return view('payments.payments',compact('payments'));
    }

    public function loanRepayment(Request $request){
        $valid_data =$this->validate($request,[
            'payment_date' =>'required',
            'paid_amount'  =>'required',
            'payment_reference'  =>'required',
            'payment_method'     =>'required',
            'payment_channel'    =>'required',
            'contract_uuid'    =>'required',
        ]);

        $check_payment =Payment::where('payment_reference',$valid_data['payment_reference'])->where('loan_contract_id','!=',null)->first();
        $loan_contract =LoanContract::where('uuid',$valid_data['contract_uuid'])->first();
        if ($check_payment) {
            return response()->json([
                'success' =>false,
                'errors'  =>'Payment Already exist',
            ],500);
        }

        $payment =Payment::where('payment_reference',$valid_data['payment_reference'])->first();

        if (!$payment) {
            $payment =Payment::create([
                'phone_number' =>$loan_contract->customer->phone_number,
                'amount'       =>$valid_data['paid_amount'],
                'payment_reference'       =>$valid_data['payment_reference'],
                'payment_method'       =>$valid_data['payment_method'],
                'payment_channel'       =>$valid_data['payment_channel'],
                'payment_date'          =>$valid_data['payment_date'],
                'added_by'              =>Auth::user()->id,
                'uuid'                  =>(string)Str::orderedUuid(),
                'status'                 =>"Posted",
                'loan_contract_id'      =>$loan_contract->id,
                'customer_id'           =>$loan_contract->customer_id,
            ]);
        }

        $installment =new InstallmentService();
        $installment_result =$installment->updateInstallment($payment);

        $payment->remarks ="Loan Repayment";
        $payment->status  ="Success";
         $payment->save();

        return response()->json([
            'success' =>true,
            'message' =>'Action Done Successfully',
        ],200);

        
        
    }
}
