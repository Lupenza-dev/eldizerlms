<?php

namespace App\Services\Loan;
use App\Models\Payment\Payment;
use App\Models\Loan\LoanContract;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\Installment;

class InstallmentService
{
    // public function __construct()
    // {
    //     //
    // }

    public function updateInstallment($payment){

        $contract     =LoanContract::where('id',$payment->loan_contract_id)->first();
       // $payment      =Payment::where('payment_reference',$payment->payment_reference)->first();

         $paid_amount = $payment->amount;

         $excess_amount = 0;
         while($paid_amount > 0){
             $installment =Installment::where('loan_contract_id',$payment->loan_contract_id)
                                     ->where('outstanding_amount','>',0)
                                     ->orderby('installment_order','ASC')
                                     ->first();
             if(!$installment){
                 $excess_amount = $paid_amount;
                 break;
             }
             //$original_paid_amount = $paid_amount;
             $current_penalt_amount_paid = 0;
             $penalt_amount_paid = 0;
             $penalt_amount = 0;


            

             if($installment->penalt_amount > 0){

                $remain_amount= $paid_amount - $installment->penalt_amount;

                 if($remain_amount < 0){
                     $current_penalt_amount_paid = $paid_amount;
                     $penalt_amount = $installment->penalt_amount - $current_penalt_amount_paid;
                     $penalt_amount_paid = $installment->penalt_amount_paid + $current_penalt_amount_paid;
                 }else{
                     $current_penalt_amount_paid = $installment->penalt_amount;
                     $penalt_amount = 0;
                     $penalt_amount_paid = $installment->penalt_amount_paid + $current_penalt_amount_paid;
                 }

             }else{
                $penalt_amount_paid = $installment->penalt_amount_paid;
             }


             $paid_amount = $paid_amount - $current_penalt_amount_paid;
             
             $paid_out_amount =0;
            
             if($installment->outstanding_amount > $paid_amount){
                 $paid_out_amount = $paid_amount;
                 $outstanding_amount = $installment->outstanding_amount - $paid_out_amount;
                 $current_balance = $installment->current_balance + $paid_out_amount;
                 $status = "OPEN";
             }else{
                 $paid_out_amount = $installment->outstanding_amount;
                 $outstanding_amount = 0;
                 $current_balance = $installment->current_balance + $paid_out_amount;
                 $status = "CLOSED";
             }

             if($penalt_amount_paid > 0 ){
                 $past_due_amount = $installment->past_due_amount - $paid_out_amount;
             }else{
                 $past_due_amount = $installment->past_due_amount;
             }

                 $installment->current_balance            =$current_balance;
                 $installment->outstanding_amount         =$outstanding_amount;
                 $installment->past_due_amount            =$past_due_amount;
                 $installment->penalt_amount_paid         =$penalt_amount_paid;
                 $installment->penalt_amount              =$penalt_amount;
                 $installment->last_paid_amount           =$paid_out_amount;
                 $installment->status                     =$status;
                 $installment->save();




                 $amount_tosettle = $installment->outstanding_amount + $installment->penalt_amount;

                 $total_paid_amount = $paid_out_amount + $current_penalt_amount_paid;

                //  $installment_payment =InstallmentPayment::create([
                //     'installment_id' =>$installment->id,
                //     'amount'         =>$total_paid_amount,
                //     'reference'      =>$reference,
                //     'uuid'           =>(string)Str::orderedUuid(),
                //  ]);

               //  $installment_payment =$installment_payment->register($installment->id, $total_paid_amount, $reference);

                 $paid_amount = $paid_amount - $paid_out_amount;
                                     
             
         }
         
         $installment_ =Installment::where('loan_contract_id',$payment->loan_contract_id)
                                     ->where('outstanding_amount','>',0)
                                     ->orderby('installment_order','ASC')
                                     ->first();

         $cont_due_day = $contract->past_due_days;
         $next_payment_date = today();
         if($installment_){
             $next_payment_date = $installment_->payment_date;
             $cont_due_day = $installment_->due_days;
         }
         

         $installments =$contract->installments;

         $outstanding_amount = $installments->sum('outstanding_amount');
         $current_balance    = $installments->sum('current_balance');
         $penalt_amount      = $installments->sum('penalt_amount');
         $past_due_amount    = $installments->sum('past_due_amount');
         $penalt_amount_paid = $installments->sum('penalt_amount_paid');

       $contract->current_balance = $current_balance; 
       $contract->date_of_last_payment = $payment->created_at;
       $contract->last_payment_amount = $payment->amount;
       $contract->next_payment_date = $next_payment_date;
       $contract->outstanding_amount = $outstanding_amount;
       $contract->excess_amount = $excess_amount;
       $contract->past_due_days = $cont_due_day;
       $contract->past_due_amount = $past_due_amount;
       $contract->penalt_amount = $penalt_amount;
       $contract->penalt_amount_paid = $penalt_amount_paid;
       $contract->save();

    //    $payment->loan_contract_id =$contract->id;
    //    $payment->save();

       $this->updateStatus($contract);

       return $payment;
       



    }

    public function updateStatus($loanContract){
        if ($loanContract->outstanding_amount <= 0) {
            ### update loan Application
            $loanContract->status ="CLOSED";
            $loanContract->save();

            $loan =LoanApplication::where('id',$loanContract->loan_application_id)->first();
            if ($loan) {
                $loan->level ="CLOSED";
                $loan->save();
            }
           
        }
    }
}
