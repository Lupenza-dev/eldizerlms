<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment\DisbursmentPayment;
use App\Models\Payment\Payment;
use App\Models\Loan\LoanContract;
use App\Models\Management\NMBConsentRequest;
use App\Models\Management\NMBSubscription;
use App\Services\Loan\InstallmentService;
use Auth;
use Illuminate\Support\Facades\Http;
use Str;


class PaymentController extends Controller
{
    public function disbursments(Request $request){
        $payments =DisbursmentPayment::with('loan_contract','loan_contract.customer')->orderBy('payment_date','DESC')->get();
        return view('payments.disbursments',compact('payments'));
    }

    public function payments(){
        $payments =Payment::with('loan_contract','loan_contract.customer')->orderBy('payment_date','DESC')->get();
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

    public function nmbSubscribers(){
        $subscribers =NMBSubscription::with('consent_request')->get();
        return view('payments.nmb_subscribers',compact('subscribers'));
    }

    public function nmbCreateTransaction(Request $request){
        $consent =NMBConsentRequest::where(['uuid'=>$request->uuid,'Status'=>'ACCEPTED'])->first();

        if(!$consent){
            return response()->json([
                'success' =>false,
                'errors'  =>'Customer Doesnot Consent',
            ],500);
        }

        $base_url     =env('BASE_URL');
        // $my_bank_id   =env('ELDIZER_BANK');
        $my_bank_id =$consent->from_bank_id;
        $user_account =$consent->from_account_number;
        $view_id      =$consent->view_id;
        $response = Http::withHeaders([
            'Consent-Id' => $consent->consent_id,
            'Consent-JWT' => $consent->jwt,
            'Cookie' =>  env('COOKIE'),
        ])
        ->withBody(json_encode([
            'to' => [
                'counterparty_id' =>implode('', $consent->counterparty_id),
            ],
            'value' => [
                'currency' => 'TZS',
                'amount' => $request->amount,
            ],
            'description' => 'This is a good test',
            'charge_policy' => 'RECEIVER',
            'attributes' => [
                [
                    'name' => 'Reference_number',
                    'attribute_type' => 'STRING',
                    'value' => '123',
                ],
                [
                    'name' => 'invoice_number_dog',
                    'attribute_type' => 'STRING',
                    'value' => '789',
                ],
            ],
        ]), 'application/json')
        ->post("$base_url/obp/v5.1.0/banks/$my_bank_id/accounts/$user_account/$view_id/transaction-request-types/COUNTERPARTY/transaction-requests");

        // Handle the response
        if ($response->successful()) {
            // Success: Process the response
            return response()->json([
                'success' =>true,
                'message' =>'Transaction created Successfully',
            ],200);
        } else {
            $error = $response->json();
            return response()->json([
                'error_code' => $error['code'] ?? 'Unknown error code',
                'errors' => $error['message'] ?? 'Unknown error message',
            ],500);
        }

    }
}
