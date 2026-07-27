<?php

namespace App\Observers;

use App\Models\Loan\LoanContract;
use App\Models\Management\CustomerMandate;
use App\Models\Payment\MandatePaymentCollection;
use App\Models\Payment\Payment;
use App\Services\InstallmentService;
use App\Services\Loan\InstallmentService as LoanInstallmentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// use App\Models\MandatePaymentCollection;

class MandatePaymentCollectionObserver
{
    /**
     * Handle the MandatePaymentCollection "created" event.
     */
    public function created(MandatePaymentCollection $mandatePaymentCollection): void
    {
        // after creation update payment
        //   $check_payment = Payment::where('payment_reference', $mandatePaymentCollection->reference)
        //   ->where('loan_contract_id', '!=', null)->first();

          $customer_mandate = CustomerMandate::where('mandate_reference', $mandatePaymentCollection->mandate_reference)->first();

        $loan_contract = LoanContract::where('loan_application_id', $customer_mandate->loan_application_id)->first();
        // if ($check_payment) {
        //     return response()->json([
        //         'success' => false,
        //         'errors'  => 'Payment Already exist',
        //     ], 500);
        // }

        $payment = Payment::where('payment_reference', $mandatePaymentCollection->reference)->first();

        if (!$payment && $loan_contract) {
            $payment = Payment::create([
                'phone_number' => $loan_contract->customer->phone_number,
                'amount'       => $mandatePaymentCollection->current_balance,
                'payment_reference'       => $mandatePaymentCollection->reference,
                'payment_method'       => 'Mandate',
                'payment_channel'       => $mandatePaymentCollection->channel,
                'payment_date'          => $mandatePaymentCollection->payment_date,
                'added_by'              => 1,
                'uuid'                  => (string)Str::orderedUuid(),
                'status'                 => "Posted",
                'loan_contract_id'      => $loan_contract->id,
                'customer_id'           => $loan_contract->customer_id,
            ]);

            $installment = new LoanInstallmentService();
            $installment_result = $installment->updateInstallment($payment);
    
            $payment->remarks = "Loan Repayment";
            $payment->status  = "Success";
            $payment->save();
        }

       

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Action Done Successfully',
        // ], 200);
    }

    /**
     * Handle the MandatePaymentCollection "updated" event.
     */
    public function updated(MandatePaymentCollection $mandatePaymentCollection): void
    {
        //
    }

    /**
     * Handle the MandatePaymentCollection "deleted" event.
     */
    public function deleted(MandatePaymentCollection $mandatePaymentCollection): void
    {
        //
    }

    /**
     * Handle the MandatePaymentCollection "restored" event.
     */
    public function restored(MandatePaymentCollection $mandatePaymentCollection): void
    {
        //
    }

    /**
     * Handle the MandatePaymentCollection "force deleted" event.
     */
    public function forceDeleted(MandatePaymentCollection $mandatePaymentCollection): void
    {
        //
    }
}
