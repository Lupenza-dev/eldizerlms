<?php

namespace App\Http\Controllers\Api\Mobile\V1;

use App\Event\LoanApplied;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Loan\LoanCalculatorService;
use App\Services\Loan\LoanApplicationService;
use App\Models\Loan\LoanProduct;
use DomainException;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoanApplicationStoreRequest;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanApproval;
use App\Models\Loan\Guarantor;
use Auth;
use Str;
use App\Http\Resources\LoanApplicationResource;
use App\Models\Loan\EcoWaterApplicants;
use App\Models\Management\Customer;
use App\Models\Management\CustomerBankDetail;
use App\Models\Management\CustomerMandate;
use App\Models\Payment\MandatePaymentCollection;
use App\Models\Payment\PaymentMandate;
use Illuminate\Support\Facades\Log;

class LoanApplicationController extends Controller
{
    /**
     * Active loan products for the app to render dynamically.
     */
    public function loanProducts(){
        $products =LoanProduct::active()->get()->map(fn (LoanProduct $p) => [
            'id'                        =>$p->id,
            'uuid'                      =>$p->uuid,
            'name'                      =>$p->name,
            'code'                      =>$p->code,
            'description'               =>$p->description,
            'icon'                      =>$p->icon,
            'color'                     =>$p->color,
            'plan_months'               =>$p->plan_months,
            'min_amount'                =>$p->min_amount,
            'max_amount'                =>$p->max_amount,
            'interest_charge'           =>$p->interest_label,
            'fees_and_charges'          =>$p->fees_label,
            'penalty'                   =>$p->penalty_label,
            'requires_agent'            =>$p->requires_agent,
            'requires_device'           =>$p->requires_device,
            'allows_initial_deposit'    =>$p->allows_initial_deposit,
            'requires_eco_water_campus' =>$p->requires_eco_water_campus,
        ]);

        return response()->json([
            'success' =>true,
            'data'    =>$products,
        ],200);
    }

    public function loanCalculator(Request $request){
        $product =LoanProduct::resolve($request->loan_product_id, $request->loan_type);

        if (!$product) {
            return response()->json([
                'success' =>false,
                'errors'  =>"Invalid loan product",
            ],422);
        }

        $calculator =LoanCalculatorService::calculator([
            'product'         =>$product,
            'amount'          =>$request->amount,
            'device_name'     =>$request->device_name,
            'device_id'       =>$request->device_id,
            'initial_deposit' =>$request->initial_deposit ?? 0,
        ]);

        return response()->json([
            'success' =>true,
            'data'    =>$calculator
        ],200);
    }

    public function loanApplication(LoanApplicationStoreRequest $request, LoanApplicationService $service){
        $customer =Customer::where('id',Auth::user()->customer_id ?? 3)->first();

        if (!$customer) {
            return response()->json([
                'success' =>false,
                'errors'  =>"Customer not available !!!!!",
            ],500);
        }

        $product =LoanProduct::resolve($request->loan_product_id, $request->loan_type);

        if (!$product) {
            return response()->json([
                'success' =>false,
                'errors'  =>"Invalid or inactive loan product",
            ],500);
        }

        $validator =Validator::make($request->all(), LoanApplicationService::rulesFor($product));

        if ($validator->fails()) {
            return response()->json([
                'message' =>'Validation error',
                'errors'  =>$validator->errors(),
            ],500);
        }

        try {
            $loan_application =$service->create($customer, $product, $validator->validated());
        } catch (DomainException $e) {
            return response()->json([
                'success' =>false,
                'errors'  =>"You have an active Loan , Please close that in order to apply another Loan !!!!!",
            ],500);
        }

        // $guarantor1 =Guarantor::create([
        //     'customer_id' =>Auth::user()->customer_id ?? 3,
        //     'loan_application_id' =>$loan_application->id,
        //     'full_name'           =>$request->guarantor1fs,
        //     'relationship'        =>$request->guarantor1rs,
        //     'phone_number'        =>$request->guarantor1pn,
        //     'uuid'                =>(string)Str::orderedUuid(),
        // ]);

        // $guarantor2 =Guarantor::create([
        //     'customer_id' =>Auth::user()->customer_id ?? 3,
        //     'loan_application_id' =>$loan_application->id,
        //     'full_name'           =>$request->guarantor2fs,
        //     'relationship'        =>$request->guarantor2rs,
        //     'phone_number'        =>$request->guarantor2pn,
        //     'uuid'                =>(string)Str::orderedUuid(),
        // ]);

       // event (new LoanApplied($loan_application,1));

        return response()->json([
            'success' =>true,
            'message' =>'Action Done Successfully Wait for the Approval',
            'data'    =>new LoanApplicationResource($loan_application),
           ],200
        );


    }

    public function eMakatocallBack(Request $request)
    {
        Log::info('------from call bak-------');
        Log::info(json_encode($request->all()));
        if ($request['callback_type'] == 'MANDATE_STATUS') {
              PaymentMandate::updateOrCreate([
                'reference' =>$request['mandate']['reference']
            ],[
                'channel' =>$request['channel'],
                'periodicity' =>$request['mandate']['periodicity'],
                'debit_type' =>$request['mandate']['debit_type'],
                'installment_amount' =>$request['mandate']['installment_amount'],
                'min_installment_amount' =>$request['mandate']['min_installment_amount'],
                'max_installment_amount' =>$request['mandate']['max_installment_amount'],
                'total_amount' =>$request['mandate']['total_amount'],
                'paid_amount' =>$request['mandate']['paid_amount'],
                'outstanding_amount' =>$request['mandate']['outstanding_amount'],
                'number_of_installment' =>$request['mandate']['number_of_installment'],
                'start_date' =>$request['mandate']['start_date'],
                'end_date' =>$request['mandate']['end_date'],
                'contract_status' =>$request['mandate']['contract_status'] ?? "Status",
                'lifecycle_status' =>$request['mandate']['lifecycle_status'] ?? null,
                'remarks' =>$request['mandate']['remarks'] ?? null,
                'approved' =>$request['mandate']['approved'] ?? "Approved",
            ]);

            $customer =CustomerMandate::where('mandate_reference',$request['mandate']['reference'])->first();
            if( $customer){
                $customer->update([
                    'status' =>$request['mandate']['lifecycle_status'] ?? null,
                ]);
            }
        } else if ($request['callback_type'] == 'COLLECTION_STATUS'){
            MandatePaymentCollection::updateOrCreate([
                'mandate_reference' =>$request['collection']['mandate']['reference'],
                'reference' =>$request['collection']['reference'],
            ],[
                // 'installment_order' =>1,
                'installment_amount' =>$request['collection']['installment_amount'],
                'current_balance' =>$request['collection']['current_balance'],
                'outstanding_amount' =>$request['collection']['outstanding_amount'],
                'payment_date' =>$request['collection']['payment_date'],
                'last_paid_amount' =>$request['collection']['last_paid_amount'],
                'status' =>$request['collection']['status'],
                'channel' =>$request['channel'],
                'remarks' =>$request['collection']['remarks'],
            ]);
        }
        else {
             return response()->json([
            'success' => false,
            'message' => 'Callback Failed to process',
            'data' => $request->all()
        ],500);
        }
        
        // Handle eMakato callback here
        return response()->json([
            'success' => true,
            'message' => 'Callback received successfully',
            'data' => $request->all()
        ]);
    }
}
