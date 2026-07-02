<?php

namespace App\Jobs;

use App\Models\Payment\MandatePaymentCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoadMandatePaymentCollection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $reference;
    public function __construct(public string $mandateReference)
    {
        $this->reference = $this->mandateReference;
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
                    ->post(env('SOLOCODE_BASE_URL').''.'collections/filtering',
                    [
                        'reference' => $this->reference
                    ]);
        $result =json_decode($response,true);
        Log::info($response);
        if ( $result['success']) {

            foreach ($result['data']['collections'] as $collection) {
                 MandatePaymentCollection::updateOrCreate([
                'reference' =>$collection['reference'],
                'mandate_reference' =>$collection['mandate']['reference'],
            ],[
                'installment_order' =>$collection['installment_order'],
                'installment_amount' =>$collection['installment_amount'],
                'min_installment_amount' =>$collection['min_installment_amount'],
                'installment_amount' =>$collection['installment_amount'],
                'max_installment_amount' =>$collection['min_installment_amount'],
                'current_balance' =>$collection['current_balance'],
                'outstanding_amount' =>$collection['outstanding_amount'],
                'payment_date' =>$collection['payment_date'],
                'last_paid_amount' =>$collection['last_paid_amount'],
                'status' =>$collection['status'],
                'remarks' =>$collection['remarks'],
            ]);
            }
           
        } else {
            Log::error("load all payment collection failed,$response");
        }
        
       } catch (\Throwable $th) {
            Log::error("load catch all payment collection failed:".json_encode($th->getMessage()));
       }
    }
}
