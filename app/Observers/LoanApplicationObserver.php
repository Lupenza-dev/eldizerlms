<?php

namespace App\Observers;

use App\Jobs\SendEmailJob;
use App\Models\Loan\LoanApplication;
use App\Models\Management\CustomerBankDetail;
use App\Models\Management\CustomerMandate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Str;
// use Log;

class LoanApplicationObserver
{
    /**
     * Handle the LoanApplication "created" event.
     */
    public function created(LoanApplication $loanApplication): void
    {
    //     $customer_name   =$loanApplication->customer?->customer_name;
    //     $student_reg     =$loanApplication->customer?->student?->student_reg_id;
    //     $message         =$customer_name." with Student ID ".$student_reg." has applied Loan Through Our Application Please Review It and Provide Feedback Through Portal";
    //    // $receiver_email  =$loanApplication->loan_approval?->agent?->email;
    //     $receiver_email  ="lupenza10@gmail.com";
    //     $receiver_name   =$loanApplication->loan_approval?->agent?->name;
    //     $subject         ="Loan Application";
    //     SendEmailJob::dispatch($message,$receiver_email,$receiver_name,$subject)->onQueue('emails');

    // send CRDB Mandate
    // $customer_bank  =CustomerBankDetail::where('id',$loanApplication->customer_bank_detail_id)
    // ->where('bank_name','CRDB')
    // ->first();

    // if ($customer_bank) {
    //         $token_request =getApiToken();
    //         Log::info('-----result---data needed now');
    //         Log::info($token_request['data']);
    //         $payload=[
    //             // 'corporate_reference' =>$token_request['data']['corporate_reference'],
    //             'channel'             =>'BANK',
    //             'payment_channel'      =>$customer_bank->bank_name,
    //             // 'account_name'        =>'Luhangano Lupenza',
    //             'account_name'        =>$loanApplication->customer?->customer_name,
    //             'account_number'    =>$customer_bank->account_number,
    //             // 'account_number'    =>'0152784219300',
    //             'phone'             =>'0'.substr($loanApplication->customer?->phone_number ?? '', -9),
    //             'NIN'               =>$loanApplication->customer?->id_number,
    //             'address'           =>$loanApplication->customer?->region?->name,
    //             'total_amount'       =>$loanApplication->loan_amount * 2,
    //             'debit_type'        =>'FIXED',
    //             'min_installment_amount' =>$loanApplication->installment_amount,
    //             'installment_amount'    =>$loanApplication->installment_amount,
    //             'periodicity'       =>'MONTHLY',
    //             'deduction_start_date' =>now()->addDays(30)->format('Y-m-d'),
    //         ];

    //         $customer_mandate =CustomerMandate::create([
    //             'customer_id' =>$loanApplication->customer_id,
    //             'loan_application_id' =>$loanApplication->id,
    //             'payload' =>json_encode($payload),
    //             'uuid' =>(string)Str::orderedUuid(),
    //          ]);

    //          try {
    //             $response =Http::withToken($token_request['data']['token'])
    //             ->post(env('SOLOCODE_BASE_URL').''.'mandate/create',$payload);
    //             $result =json_decode($response,true);
    //             Log::info("++++++++++Result of mandate++++++++++++");
    //             Log::info($result);
    //             if ($result['success']) {
    //                 $customer_mandate->update([
    //                     'mandate_reference' => $result['data']['mandate_reference'],
    //                     'status'     => $result['data']['status'],
    //                 ]);
    //             } else {
    //                 Log::error('Error Mandate creation Error: '.$result);
    //             }
                

    //         } catch (\Throwable $th) {
    //             Log::error('Error Mandate creation Error: '.$th->getMessage());
    //             // return ['success'=>false,'message'=>$th->getMessage()];
    //         }

    // }
}

    /**
     * Handle the LoanApplication "updated" event.
     */
    public function updated(LoanApplication $loanApplication): void
    {
        //
    }

    /**
     * Handle the LoanApplication "deleted" event.
     */
    public function deleted(LoanApplication $loanApplication): void
    {
        //
    }

    /**
     * Handle the LoanApplication "restored" event.
     */
    public function restored(LoanApplication $loanApplication): void
    {
        //
    }

    /**
     * Handle the LoanApplication "force deleted" event.
     */
    public function forceDeleted(LoanApplication $loanApplication): void
    {
        //
    }
}
