<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Jobs\LoadMandatePaymentCollection;
use App\Jobs\LoadPaymentMandates;
use Illuminate\Http\Request;
use App\Models\Payment\DisbursmentPayment;
use App\Models\Payment\Payment;
use App\Models\Loan\LoanContract;
use App\Models\Management\CustomerMandate;
use App\Models\Management\NMBConsentRequest;
use App\Models\Management\NMBSubscription;
use App\Models\Payment\MandatePaymentCollection;
use App\Models\Payment\PaymentMandate;
use App\Models\Payment\PaymentRequest;
use App\Services\Loan\InstallmentService;
use Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Str;


class PaymentController extends Controller
{
    public function disbursments(Request $request)
    {
        $payments = DisbursmentPayment::with('loan_contract', 'loan_contract.customer')->orderBy('payment_date', 'DESC')->get();
        return view('payments.disbursments', compact('payments'));
    }

    public function payments()
    {
        $payments = Payment::with('loan_contract', 'loan_contract.customer')->orderBy('payment_date', 'DESC')->get();
        return view('payments.payments', compact('payments'));
    }

    public function loanRepayment(Request $request)
    {
        $valid_data = $this->validate($request, [
            'payment_date' => 'required',
            'paid_amount'  => 'required',
            'payment_reference'  => 'required',
            'payment_method'     => 'required',
            'payment_channel'    => 'required',
            'contract_uuid'    => 'required',
        ]);

        $check_payment = Payment::where('payment_reference', $valid_data['payment_reference'])->where('loan_contract_id', '!=', null)->first();
        $loan_contract = LoanContract::where('uuid', $valid_data['contract_uuid'])->first();
        if ($check_payment) {
            return response()->json([
                'success' => false,
                'errors'  => 'Payment Already exist',
            ], 500);
        }

        $payment = Payment::where('payment_reference', $valid_data['payment_reference'])->first();

        if (!$payment) {
            $payment = Payment::create([
                'phone_number' => $loan_contract->customer->phone_number,
                'amount'       => $valid_data['paid_amount'],
                'payment_reference'       => $valid_data['payment_reference'],
                'payment_method'       => $valid_data['payment_method'],
                'payment_channel'       => $valid_data['payment_channel'],
                'payment_date'          => $valid_data['payment_date'],
                'added_by'              => Auth::user()->id,
                'uuid'                  => (string)Str::orderedUuid(),
                'status'                 => "Posted",
                'loan_contract_id'      => $loan_contract->id,
                'customer_id'           => $loan_contract->customer_id,
            ]);
        }

        $installment = new InstallmentService();
        $installment_result = $installment->updateInstallment($payment);

        $payment->remarks = "Loan Repayment";
        $payment->status  = "Success";
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Action Done Successfully',
        ], 200);
    }

    public function loanRepaymentRequest(Request $request)
    {
        $valid_data = $this->validate($request, [
            'payment_date'      => 'required|date',
            'paid_amount'       => 'required|numeric|min:1',
            'payment_reference' => 'required|string',
            'payment_method'    => 'required|string',
            'payment_channel'   => 'required|string',
            'contract_uuid'     => 'required',
        ]);

        $loan_contract = LoanContract::with('customer')->where('uuid', $valid_data['contract_uuid'])->first();
        if (!$loan_contract) {
            return response()->json([
                'success' => false,
                'errors'  => 'Loan contract not found',
            ], 404);
        }

        if ($loan_contract->status == 'CLOSED') {
            return response()->json([
                'success' => false,
                'errors'  => 'Loan contract already closed',
            ], 422);
        }

        $payment_exists = Payment::where('payment_reference', $valid_data['payment_reference'])
            ->whereNotNull('loan_contract_id')
            ->exists();
        $request_exists = PaymentRequest::where('payment_reference', $valid_data['payment_reference'])
            ->whereIn('status', ['pending', 'success'])
            ->exists();

        if ($payment_exists || $request_exists) {
            return response()->json([
                'success' => false,
                'errors'  => 'Payment Already exist',
            ], 500);
        }

        PaymentRequest::create([
            'uuid'              => (string) Str::orderedUuid(),
            'phone_number'      => optional($loan_contract->customer)->phone_number,
            'amount'            => $valid_data['paid_amount'],
            'payment_reference' => $valid_data['payment_reference'],
            'payment_method'    => $valid_data['payment_method'],
            'payment_channel'   => $valid_data['payment_channel'],
            'payment_date'      => $valid_data['payment_date'],
            'loan_contract_id'  => $loan_contract->id,
            'customer_id'       => $loan_contract->customer_id,
            'added_by'          => Auth::id(),
            'status'            => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Repayment request submitted and pending approval',
        ], 200);
    }

    public function allPayments()
    {
        return view('payments.all_payments');
    }

    public function paymentRequests()
    {
        $payment_requests = PaymentRequest::with('loan_contract.customer', 'addedBy')
            ->whereIn('status', ['pending', 'rejected'])
            ->orderByRaw("FIELD(status, 'pending', 'rejected')")
            ->latest()
            ->get();

        return view('payments.payment_requests', compact('payment_requests'));
    }

    public function approvePaymentRequest(Request $request)
    {
        $valid_data = $this->validate($request, [
            'uuid' => 'required',
        ]);

        $payment_request = PaymentRequest::where('uuid', $valid_data['uuid'])->first();
        if (!$payment_request) {
            return response()->json([
                'success' => false,
                'errors'  => 'Payment request not found',
            ], 404);
        }

        // TODO: approval logic (create Payment, update installments, mark request as success).

        $check_payment = Payment::where('payment_reference',$payment_request->payment_reference)->where('loan_contract_id', '!=', null)->first();
        $loan_contract = LoanContract::where('id', $payment_request->loan_contract_id)->first();
        if ($check_payment) {
            return response()->json([
                'success' => false,
                'errors'  => 'Payment Already exist',
            ], 500);
        }

        $payment = Payment::where('payment_reference',$payment_request->payment_reference)->first();

        if (!$payment) {
            $payment = Payment::create([
                'phone_number' => $loan_contract->customer->phone_number,
                'amount'       => $payment_request->amount,
                'payment_reference'       =>$payment_request->payment_reference,
                'payment_method'       => $payment_request->payment_method,
                'payment_channel'       => $payment_request->payment_channel,
                'payment_date'          => $payment_request->payment_date,
                'added_by'              => Auth::user()->id,
                'uuid'                  => (string)Str::orderedUuid(),
                'status'                 => "Posted",
                'loan_contract_id'      => $loan_contract->id,
                'customer_id'           => $loan_contract->customer_id,
            ]);
        }

        $installment = new InstallmentService();
        $installment_result = $installment->updateInstallment($payment);

        $payment_request->update([
            'status'  => 'success',
            'remarks' => "Posted Successfully",
        ]);

        $payment->remarks = "Loan Repayment";
        $payment->status  = "Success";
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => 'Action Done Successfully',
        ], 200);


    }

    public function rejectPaymentRequest(Request $request)
    {
        $valid_data = $this->validate($request, [
            'uuid'    => 'required',
            'remarks' => 'required|string|min:3',
        ], [
            'remarks.required' => 'Please provide the reason for rejecting this payment request',
        ]);

        $payment_request = PaymentRequest::where('uuid', $valid_data['uuid'])->first();
        if (!$payment_request) {
            return response()->json([
                'success' => false,
                'errors'  => 'Payment request not found',
            ], 404);
        }

        if ($payment_request->status !== 'pending') {
            return response()->json([
                'success' => false,
                'errors'  => 'Only pending payment requests can be rejected',
            ], 422);
        }

        $payment_request->update([
            'status'  => 'rejected',
            'remarks' => $valid_data['remarks'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment request rejected',
        ], 200);
    }

    public function nmbSubscribers()
    {
        $subscribers = NMBSubscription::with('consent_request')->get();
        return view('payments.nmb_subscribers', compact('subscribers'));
    }

    public function nmbCreateTransaction(Request $request)
    {
        $consent = NMBConsentRequest::where(['uuid' => $request->uuid, 'Status' => 'ACCEPTED'])->first();

        if (!$consent) {
            return response()->json([
                'success' => false,
                'errors'  => 'Customer Doesnot Consent',
            ], 500);
        }

        $base_url     = env('BASE_URL');
        $my_bank_id = $consent->from_bank_id;
        $user_account = $consent->from_account_number;
        $view_id      = $consent->view_id;
        $response = Http::withHeaders([
            'Consent-Id' => $consent->consent_id,
            'Consent-JWT' => $consent->jwt,
            'Cookie' =>  env('COOKIE'),
        ])
            ->withBody(json_encode([
                'to' => [
                    'counterparty_id' => implode('', $consent->counterparty_id),
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
                'success' => true,
                'message' => 'Transaction created Successfully',
            ], 200);
        } else {
            $error = $response->json();
            return response()->json([
                'error_code' => $error['code'] ?? 'Unknown error code',
                'errors' => $error['message'] ?? 'Unknown error message',
            ], 500);
        }
    }

    public function paymentManagement()
    {
        return view('payments.payment_management');
    }

    public function paymentMandates()
    {
        $payments = PaymentMandate::with('customer_mandate', 'customer_mandate.customer')->orderBy('start_date', 'desc')->get();
        return view('payments.payment_mandates', compact('payments'));
    }

    public function syncMandates()
    {
        LoadPaymentMandates::dispatch();
        return back()->with('success', 'Payment mandates sync job dispatched successfully');
    }

    public function syncMandatePaymentCollection($reference)
    {
        LoadMandatePaymentCollection::dispatch($reference);
        return back()->with('success', 'Mandate payment collection sync job dispatched successfully');
    }

    public function viewPaymentMandate($reference)
    {
        $payment = PaymentMandate::with('customer_mandate', 'customer_mandate.customer')->where('reference', $reference)->first();
        $collections = MandatePaymentCollection::where('mandate_reference', $reference)->get();

        if (!$payment) {
            return redirect()->back()->with('error', 'Payment mandate not found');
        }
        return view('payments.mandate_profile', compact('payment', 'collections'));
    }

    public function cancelMandate(Request $request)
    {
        $valid_data = $this->validate($request, [
            'reference'   => 'required',
            'description' => 'required|string',
        ]);

        $token_request = getApiToken();
        // Log::info("++++++send++++");
        // Log::info($valid_data['reference']);
        // Log::info($valid_data['description']);
        // Log::info($token_request['data']['token']);
        // Log::info(env('SOLOCODE_BASE_URL') . '' . 'mandate/cancellation');
        $response = Http::withToken($token_request['data']['token'])
            ->post(
                env('SOLOCODE_BASE_URL') . '' . 'mandate/cancellation',
                [
                    'reference' => $valid_data['reference'],
                    'reasons' => $valid_data['description'],
                ]
            );
         $result =json_decode($response,true);

       // Log::info($response);


        if ($result['success']) {
            return response()->json([
            'success' => true,
            'message' => 'Action completed successfully',
        ],200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel mandate',
            ],500);
        }


    }

    public function customerLoansMandates()
    {
        $payments = CustomerMandate::with('customer', 'loanApplication')->orderBy('created_at', 'desc')->get();
        return view('payments.customer_loans_mandates', compact('payments'));
    }

    public function resendMandateOtp(Request $request)
    {
        $valid_data = $this->validate($request, [
            'reference' => 'required',
        ]);

        $token_request = getApiToken();
        $response = Http::withToken($token_request['data']['token'])
            ->post(
                env('SOLOCODE_BASE_URL') . '' . 'mandate/otp/create',
                [
                    'reference' => $valid_data['reference']
                ]
            );
        $result = json_decode($response, true);
        Log::info($response);
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'OTP Resent Successfully',
            ], 200);
        } else {
            Log::error("resend mandate otp failed,$response");

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP',
            ], 500);
        }
    }

    public function verifyMandateOtp(Request $request)
    {
        $valid_data = $this->validate($request, [
            'mandate_reference' => 'required',
            'otp'               => 'required',
        ]);

        $token_request = getApiToken();
        $response = Http::withToken($token_request['data']['token'])
            ->post(
                env('SOLOCODE_BASE_URL') . '' . 'mandate/otp/verify',
                [
                    'reference' => $valid_data['mandate_reference'],
                    'otpPin' => $valid_data['otp'],
                ]
            );
        $result = json_decode($response, true);
        Log::info($response);
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'OTP Verified Successfully',
            ], 200);
        } else {
            Log::error("OTP verification failed,$response");
            return response()->json([
                'success' => false,
                'error' => $result['message'] ?? 'OTP verification failed',
            ], 500);
        }
    }
}
