<?php

namespace App\Observers;

use App\Jobs\SendEmailJob;
use App\Models\Loan\LoanApplication;

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
