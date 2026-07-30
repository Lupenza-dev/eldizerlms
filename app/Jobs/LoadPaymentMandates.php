<?php

namespace App\Jobs;

use App\Models\Management\CustomerMandate;
use App\Models\Payment\PaymentMandate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoadPaymentMandates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       try {
        Log::info('job dispatched');
          $token_request =getApiToken();
        $response =Http::withToken($token_request['data']['token'])
                    ->get(env('SOLOCODE_BASE_URL').''.'mandate/all');
        $result =json_decode($response,true);
       // Log::info('response: '.json_encode($result));

        if ( $result['success']) {

            foreach ($result['data']['mandates'] as $mandate) {
                 PaymentMandate::updateOrCreate([
                'reference' =>$mandate['reference']
            ],[
                'channel' =>$mandate['payment_channel'],
                'periodicity' =>$mandate['periodicity'],
                'debit_type' =>$mandate['debit_type'],
                'installment_amount' =>$mandate['installment_amount'],
                'min_installment_amount' =>$mandate['min_installment_amount'],
                'max_installment_amount' =>$mandate['max_installment_amount'],
                'total_amount' =>$mandate['total_amount'],
                'paid_amount' =>$mandate['paid_amount'],
                'outstanding_amount' =>$mandate['outstanding_amount'],
                'number_of_installment' =>$mandate['number_of_installment'],
                'start_date' =>$mandate['start_date'],
                'end_date' =>$mandate['end_date'],
                'contract_status' =>$mandate['contract_status'] ?? "Status",
                'lifecycle_status' =>$mandate['lifecycle_status'] ?? null,
                'remarks' =>$mandate['remarks'] ?? null,
                'approved' =>$mandate['approved'] ?? "Approved",
            ]);

            $customer =CustomerMandate::where('mandate_reference',$mandate['reference'])->first();
            if( $customer){
                $customer->update([
                    'status' =>$mandate['lifecycle_status'] ?? null,
                ]);
            }
            }
           
        } else {
            Log::error("load all payment mandate failed,$response");
        }
        
       } catch (\Throwable $th) {
            Log::error("load catch all payment mandate failed:".json_encode($th->getMessage()));
       }
    }
}
