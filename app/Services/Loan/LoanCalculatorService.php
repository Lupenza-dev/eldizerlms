<?php

namespace App\Services\Loan;
use App\Models\Loan\LoanApplication;

class LoanCalculatorService
{
    // public function __construct()
    // {
        
    // }

    public static function calculator($data){
        $loan_type = $data['loan_type'];
        $rate =($loan_type == 2)? 1.15 : 1.20;
        $amount =$data['amount'];
        $plan   =$data['plan'];

        switch($loan_type) {
            case 1:
                $total_amount = $amount * $rate;
                break;
            case 2:
                $total_amount = $amount;
                break;
            case 3:
                $total_amount = 15000;
                break;
        }
        
        $installment_amount =$loan_type == 2 ? ($amount-$data['initial_deposit'])/$plan : $amount/$plan;

        $details['total_amount']       =$total_amount;
        $details['installment_amount'] =$installment_amount;
        $details['start_date']          =date('Y-m-d');
        $details['end_date']           =date('Y-m-d', strtotime("+".$plan." months", strtotime($details['start_date'])));
        $details['interest_amount']    =$details['total_amount'] - $amount;
        $details['amount']             =$amount;
        $details['plan']               =$plan;
        $details['device_name']        =$data['device_name'] ?? null;
        $details['device_id']          =$data['device_id'] ?? null;
        $details['initial_deposit']    =$data['initial_deposit'] ?? 0;
        $details['interest_charge']    ="3.5%";
        $details['fees_and_charges']   ="9.5%";
        $details['late_payment']       ="5%";

        return $details;


    }


    public function loanCode() { 
        do
        {
            $token = $this->char_string(4);
            $code = 'EL'. $token . substr(strftime("%Y", time()),2);
            $user_code = LoanApplication::where('loan_code',$code)->first();
        }
        while(!empty($user_code));
        return $code;
    }

    private function char_string($length){    
        $token = "";
        $codeAlphabet = "ELDIZERFINANCE";
        $codeAlphabet.= "0123456789875532423458609543";

        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }
        return $token;
    }

}
