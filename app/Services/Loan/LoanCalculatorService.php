<?php

namespace App\Services\Loan;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanProduct;
use InvalidArgumentException;

class LoanCalculatorService
{
    // public function __construct()
    // {
        
    // }

    /**
     * Calculate a loan breakdown from the product configuration.
     *
     * Accepts `loan_product_id` or the legacy `loan_type` (product ids mirror 1,2,3),
     * or a resolved LoanProduct instance under `product`.
     */
    public static function calculator($data){
        $product = $data['product'] ?? LoanProduct::resolve($data['loan_product_id'] ?? null, $data['loan_type'] ?? null, false);

        if (! $product instanceof LoanProduct) {
            throw new InvalidArgumentException('Invalid loan product.');
        }

        $amount          = (float) $data['amount'];
        $plan            = max(1, (int) $product->plan_months);
        $initial_deposit = $product->allows_initial_deposit ? (float) ($data['initial_deposit'] ?? 0) : 0;

        switch ($product->calculation_method) {
            case LoanProduct::METHOD_PRINCIPAL_ONLY:
                $total_amount = $amount;
                break;
            case LoanProduct::METHOD_FIXED_AMOUNT:
                $total_amount = (float) $product->fixed_total_amount;
                break;
            case LoanProduct::METHOD_INTEREST:
            default:
                $total_amount = $amount * (1 + $product->markup_rate);
                break;
        }

        $installment_amount = $product->allows_initial_deposit ? ($amount - $initial_deposit) / $plan : $amount / $plan;

        $details['loan_product_id']    =$product->id;
        $details['loan_type']          =$product->id;
        $details['product_name']       =$product->name;
        $details['total_amount']       =$total_amount;
        $details['installment_amount'] =$installment_amount;
        $details['start_date']         =date('Y-m-d');
        $details['end_date']           =date('Y-m-d', strtotime("+".$plan." months", strtotime($details['start_date'])));
        $details['interest_amount']    =$details['total_amount'] - $amount;
        $details['amount']             =$amount;
        $details['plan']               =$plan;
        $details['device_name']        =$data['device_name'] ?? null;
        $details['device_id']          =$data['device_id'] ?? null;
        $details['initial_deposit']    =$initial_deposit;
        $details['interest_charge']    =$product->interest_label;
        $details['fees_and_charges']   =$product->fees_label;
        $details['penalty']            =$product->penalty_label;
        $details['late_payment']       =$product->penalty_label; // legacy key for mobile clients

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
