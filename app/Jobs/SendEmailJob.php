<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $message;
    public $receiver_email;
    public $receiver_name;
    public $subject;

    public function __construct($message,$receiver_email,$receiver_name,$subject)
    {
        $this->message        =$message;
        $this->receiver_email =$receiver_email;
        $this->receiver_name  =$receiver_name;
        $this->subject        =$subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data =array(
            'name' =>$this->receiver_name,
            'body' =>$this->message,
        );
        $email_subject  =$this->subject;
        $receiver_name  =$this->receiver_name;
        $receiver_email =$this->receiver_email;
       // $receiver_email ="elibarikidavid23@gmail.com";

        Mail::send('mails.mail_template',['data'=>$data],function($message) use ($email_subject,$receiver_name,$receiver_email) {
           $message->to($receiver_email, $receiver_name)->subject
              ($email_subject);
          // $message-bcc('lupenza10@gmail.com', 'Lupenza Luhangano');
           $message->from('non-reply@eldizerfinance.co.tz','Eldizer Finance Ltd Team');
        });
    }
}
