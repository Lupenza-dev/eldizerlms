<?php

namespace App\Jobs;

use App\Models\Loan\Installment;
use App\Models\Loan\LoanContract;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LoanPenaltCalculation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $notificationQueue = 'emails';

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('Penalty Calculation Job Started');
        $installments =Installment::where('status','OPEN')
        ->where('payment_date','<',Carbon::now())
        ->get();

        foreach ($installments as $installment) {
        $past_due_days =Carbon::now()->diffInDays($installment->payment_date);

        if ($installment->penalt_amount != 0 or $installment->penalt_amount_paid > 0) {
        $penalt_amount =$installment->penalt_amount;
        }else{
       // $penalt_amount =0.05 * $installment->total_amount;
        $penalt_amount =0;
        }

        $installment->due_days   =$past_due_days;
        $installment->penalt_amount   =$penalt_amount;
        $installment->past_due_amount =$penalt_amount + $installment->total_amount;
        $installment->save();

        $contract =LoanContract::with('installments')->where('id',$installment->loan_contract_id)->first();
        $high_due_inst =Installment::where('loan_contract_id',$installment->loan_contract_id)
                    ->where('outstanding_amount','>',0)
                    ->orderby('id','DESC')
                    ->first();

        $cont_due_day = $high_due_inst->due_days;

        if($contract->current_past_due_days >= $cont_due_day){
        $current_past_due_days = $contract->current_past_due_days;
        }else{
        $current_past_due_days = $cont_due_day;
        }

        $contract->penalt_amount         = $contract->installments->sum('penalt_amount');
        $contract->past_due_amount       = $contract->installments->sum('past_due_amount');
        $contract->past_due_days         = $cont_due_day;
        $contract->current_past_due_days = $current_past_due_days;
        $contract->save();

        }
        
        return true;
    }
}
