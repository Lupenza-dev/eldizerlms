<?php

namespace App\Http\Controllers\Api\Mobile\V1;

use App\Event\LoanApplied;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Loan\LoanCalculatorService;
use App\Http\Requests\LoanApplicationStoreRequest;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanApproval;
use App\Models\Loan\Guarantor;
use Auth;
use Str;
use App\Http\Resources\LoanApplicationResource;
use App\Models\Management\Customer;

class LoanApplicationController extends Controller
{
    public function loanCalculator(Request $request){
       
        $amount =$request->amount;
        $plan =$request->plan;
        $loan_type =$request->loan_type;
        $device_name =$request->device_name;
        $device_id =$request->device_id;
        $data =[
            'amount' =>$amount,
            'plan'   =>$plan,
            'loan_type' =>$loan_type,
            'device_name' =>$device_name,
            'device_id' =>$device_id
        ];

        $calculator =LoanCalculatorService::calculator($data);

        return response()->json([
            'success' =>true,
            'data'    =>$calculator
        ],200);
    }

    public function loanApplication(LoanApplicationStoreRequest $request){
        $valid_data =$request->validated();

        $customer =Customer::where('id',Auth::user()->customer_id ?? 3)->first();

        if (!$customer) {
            return response()->json([
                'success' =>false,
                'errors'  =>"Customer not available !!!!!",
            ],500);
        }

        if ($customer->initiated_loan_application()) {
            $applications =$customer->initiated_loan_application;
            
            foreach ($applications as $key) {
               
                $key->update(['level'=>"Canceled"]);

                #### cancel loan approval ####

                $approval =$key->loan_approval;

                if ($approval) {
                    $approval->status ="Canceled";
                    $approval->save();
                }

            }            
        }

        if ($customer->loan_contracts->count() > 0) {
            return response()->json([
                'success' =>false,
                'errors'  =>"You have an active Loan , Please close that in order to apply another Loan !!!!!",
            ],500);
        }

        $calculator =LoanCalculatorService::calculator($valid_data);

        $code_generator =new LoanCalculatorService;
        
        $loan_application =LoanApplication::create([
            'customer_id' =>Auth::user()->customer_id ?? 3,
            'amount'      =>$calculator['amount'],
            'loan_amount' =>$calculator['total_amount'],
            'plan'        =>$calculator['plan'],
            'installment_amount'  =>$calculator['installment_amount'],
            'interest_rate'       =>0.25,
            'interest_amount'     =>$calculator['interest_amount'],
            'loan_code'           =>$code_generator->loanCode(),
            'uuid'                =>(string)Str::orderedUuid(),
            'level'               =>"Application",
            'college_id'          =>$customer->student?->college_id
            
        ]);

        $approval =LoanApproval::create([
            'loan_application_id' =>$loan_application->id,
            'agent_id'            =>$valid_data['agent_id'],
            'uuid'                =>(string)Str::orderedUuid(),
        ]);

        $guarantor1 =Guarantor::create([
            'customer_id' =>Auth::user()->customer_id ?? 3,
            'loan_application_id' =>$loan_application->id,
            'full_name'           =>$request->guarantor1fs,
            'relationship'        =>$request->guarantor1rs,
            'phone_number'        =>$request->guarantor1pn,
            'uuid'                =>(string)Str::orderedUuid(),
        ]);

        $guarantor2 =Guarantor::create([
            'customer_id' =>Auth::user()->customer_id ?? 3,
            'loan_application_id' =>$loan_application->id,
            'full_name'           =>$request->guarantor2fs,
            'relationship'        =>$request->guarantor2rs,
            'phone_number'        =>$request->guarantor2pn,
            'uuid'                =>(string)Str::orderedUuid(),
        ]);

        event (new LoanApplied($loan_application,1));

        return response()->json([
            'success' =>true,
            'message' =>'Action Done Successfully Wait for the Approval',
            'data'    =>new LoanApplicationResource($loan_application),
           ],200
        );


    }
}
