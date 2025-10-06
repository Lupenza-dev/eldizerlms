<?php

namespace App\Observers;

use App\Models\Loan\LoanContract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class LoanContractObserver
{
    /**
     * Handle the LoanContract "created" event.
     */

    public function created(LoanContract $loanContract): void
    {
        $pdf = Pdf::loadView('pdf.loan_contract', ['loan' =>$loanContract])->output();
        $receiver_name =$loanContract->customer?->customer_name;
        $data =array(
            'name' =>$receiver_name,
            'body' =>"Kindly receive Your Loan Contract",
        );
        $email_subject ="Loan Contract With Eldizer Financial Services";
        $receiver_name =$receiver_name;
        // $receiver_email =$loanContract->customer?->email;
        $receiver_email ='lupenza10@gmail.com';

        Mail::send('mails.mail_template',['data'=>$data],function($message) use ($email_subject,$receiver_name,$receiver_email,$pdf) {
           $message->to($receiver_email, $receiver_name)->subject
              ($email_subject)
              ->attachData($pdf, 'loan_contract.pdf', [
                'mime' => 'application/pdf',
            ])
           ->from('non-reply@eldizerfinance.co.tz','Eldizer Finance Ltd Team');
        });

        // Mail::send('mails.mail_template',['data'=>$data],function($message) use ($email_subject,$receiver_name,$receiver_email) {
        //     $message->to($receiver_email, $receiver_name)->subject
        //        ($email_subject);
        //    // $message-bcc('lupenza10@gmail.com', 'Lupenza Luhangano');
        //     $message->from('non-reply@eldizerfinance.co.tz','Eldizer Finance Ltd Team');
        //  });
    }

    /**
     * Handle the LoanContract "updated" event.
     */
    public function updated(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "deleted" event.
     */
    public function deleted(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "restored" event.
     */
    public function restored(LoanContract $loanContract): void
    {
        //
    }

    /**
     * Handle the LoanContract "force deleted" event.
     */
    public function forceDeleted(LoanContract $loanContract): void
    {
        //
    }
}
