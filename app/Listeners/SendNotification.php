<?php

namespace App\Listeners;

use App\Event\LoanApplied;
use App\Jobs\SendEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoanApplied $event): void
    {
        /** Action  1 means loan application */
        if ($event->action == 1) {
            $customer_name   =$event->loanApplication->customer?->customer_name;
            $student_reg     =$event->loanApplication->customer?->student?->student_reg_id;
            $message         =$customer_name." with Student ID ".$student_reg." has applied Loan Through Our Application Please Review It and Provide Feedback Through Portal";
            $receiver_email  =$event->loanApplication->loan_approval?->agent?->email;
            $receiver_name   =$event->loanApplication->loan_approval?->agent?->name;
            $subject         ="Loan Application";
            SendEmailJob::dispatch($message,$receiver_email,$receiver_name,$subject)->onQueue('emails');
        }

        /** Action  2 means loan application approved by agent */
        if ($event->action == 2) {
            $customer_name   =$event->loanApplication->customer?->customer_name;
            $agent_name      =$event->loanApplication->loan_approval?->agent?->name;
            $message         ="Your loan Application with code ".$event->loanApplication->loan_code." has been approved by agent ".$agent_name." Please wait for Adminstration to review and work on it";
            $receiver_email  =$event->loanApplication->customer?->email;
            $receiver_name   =$customer_name;
            $subject         ="Loan Application Status";
            SendEmailJob::dispatch($message,$receiver_email,$receiver_name,$subject)->onQueue('emails');
        }

        /** Action  3 means loan application Rejected by agent */
        if ($event->action == 3) {
            $customer_name   =$event->loanApplication->customer?->customer_name;
            $agent_name      =$event->loanApplication->loan_approval?->agent?->name;
            $message         ="Your loan Application with code ".$event->loanApplication->loan_code." has been Rejected please Login to application to know the reason";
            $receiver_email  =$event->loanApplication->customer?->email;
            //$receiver_email  ="luhaboy@gmail.com";
            $receiver_name   =$customer_name;
            $subject         ="Loan Application Status";
            SendEmailJob::dispatch($message,$receiver_email,$receiver_name,$subject)->onQueue('emails');
        }
      
    }
}
