<?php

namespace App\Http\Controllers\Api\Mobile\V2;

use App\Http\Controllers\Controller;
use App\Models\Management\NMBConsentRequest;
use App\Models\Management\NMBSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Validator;
use Str;

class NmbController extends Controller
{
    public function subscribe(Request $request){
        $validator = Validator::make(
            $request->all(), [
                'username'       =>'required',
                'password'      =>'required',
                'account_number' =>'required',
            ]
        );

        if ($validator->fails() ) {
            return response()->json(
                [
                    'success' => false,
                    'error_message' => $validator->errors(),
                ], 500
            );
        }


        return $this->directLogin($request->all()); 
    }

    public function directLogin($input){
        $consumer_key =env('CONSUMER_KEY');
        $username = $input['username'];
        $password = $input['password'];
        $cookie = env('COOKIE');
        // Make the request
        $response = Http::withHeaders([
            'Authorization' => "DirectLogin username=\"$username\",password=\"$password\",consumer_key=$consumer_key",
            'Cookie'        => $cookie,
            'Content-Type'  => 'multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW',
        ])->post(env('BASE_URL').'/'."my/logins/direct");

        if ($response['token']) {
            $nmb =NMBSubscription::updateOrCreate([
                'nmb_username' =>$username,
            ],[
                'token'        =>$response['token'],
                'uuid'         =>(string)Str::orderedUuid(),
            ]);
            return $this->consentRequest($input['account_number'],$response['token'],$nmb);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'error_message' =>"Failed To Authenticate",
                ], 500
            );
        }

    }

    public function consentRequest($account,$token,$nmb_request){
        $data =[
            "consent_type"=> "VRP",
            "from_account"=> [
               "bank_routing"=> [
                    "scheme"=> "OBP",
                    "address"=> "nmbb.01.tz.nmbb"
                ],
                "account_routing"=> [
                    "scheme"=> "OBP",
                    "address"=> $account
                ],
                "branch_routing"=> [
                    "scheme"=> "",
                    "address"=> ""
                ]
            ],
            "to_account"=> [
                "counterparty_name"=>env('ELDIZER_COUNTERPART'),
                "bank_routing"=> [
                    "scheme"=> "OBP",
                    "address"=> env('ELDIZER_BANK')
                ],
                "account_routing"=> [
                    "scheme"=> "OBP",
                    "address"=> env('ELDIZER_ACCOUNT')
                ],
                "branch_routing"=> [
                    "scheme"=> "",
                    "address"=> ""
                ],
                "limit"=> [
                    "currency"=> "TZS",
                    "max_single_amount"=> 5000,
                    "max_monthly_amount"=> 50000,
                    "max_number_of_monthly_transactions"=> 3,
                    "max_yearly_amount"=> 50000,
                    "max_number_of_yearly_transactions"=> 25,
                    "max_total_amount"=> 300000,
                    "max_number_of_transactions"=> 100
                ]
            ],
            "valid_from" =>Carbon::now('UTC')->toIso8601ZuluString(),
            "time_to_live"=> 31536000,
            "email"=> "lupenza10@gmail.com",
            "phone_number"=> "255683130185"
        ];
        Log::debug($data);
        $response = Http::withHeaders([
            'Authorization' => 'DirectLogintoken='.$token.'',
            'Cookie'        => env('COOKIE'),
            'Content-Type'  => 'application/json',
        ])->post(env('BASE_URL').'/'."obp/v5.1.0/consumer/vrp-consent-requests",$data);

       if ($response['consent_request_id']) {
        // return $response['payload']['consent_type'];
        $consent =NMBConsentRequest::create([
            'nmb_subscriber_id' =>$nmb_request->id,
            'consent_request_id' =>$response['consent_request_id'],
        // ],[
            'consent_type' =>$response['payload']['consent_type'] ?? null,
            'from_account_bank_scheme' =>$response['payload']['from_account']['bank_routing']['scheme'] ?? null,
            'from_bank_id'             =>$response['payload']['from_account']['bank_routing']['address'] ?? null,
            'from_account_scheme'      =>$response['payload']['from_account']['account_routing']['scheme'] ?? null,
            'from_account_number'      =>$response['payload']['from_account']['account_routing']['address'] ?? null,
            'to_account_counterparty_name' =>$response['payload']['to_account']['counterparty_name'] ?? null,
            'to_account_bank_scheme' =>$response['payload']['to_account']['bank_routing']['scheme'] ?? null,
            'to_bank_id'             =>$response['payload']['to_account']['bank_routing']['address'] ?? null,
            'to_account_scheme'      =>$response['payload']['to_account']['account_routing']['scheme'] ?? null,
            'to_account_number'      =>$response['payload']['to_account']['account_routing']['address'] ?? null,
            'currency'                =>$response['payload']['to_account']['limit']['currency'] ?? null,
            'max_single_amount'       =>$response['payload']['to_account']['limit']['max_single_amount'] ?? null,
            'max_monthly_amount'      =>$response['payload']['to_account']['limit']['max_monthly_amount'] ?? null,
            'max_number_of_monthly_transactions'      =>$response['payload']['to_account']['limit']['max_number_of_monthly_transactions'] ?? null,
            'max_yearly_amount'                       =>$response['payload']['to_account']['limit']['max_yearly_amount'] ?? null,
            'max_number_of_yearly_transactions'      =>$response['payload']['to_account']['limit']['max_number_of_yearly_transactions'] ?? null,
            'max_total_amount'                       =>$response['payload']['to_account']['limit']['max_total_amount'] ?? null,
            'max_number_of_transactions'             =>$response['payload']['to_account']['limit']['max_number_of_transactions'] ?? null,
            'valid_from'      =>$response['payload']['valid_from'] ?? null,
            'time_to_live'      =>$response['payload']['time_to_live'] ?? null,
            'phone_number'      =>$response['payload']['phone_number'] ?? null,
            'email'      =>$response['payload']['email'] ?? null,
            'consumer_id'      =>$response['consumer_id'] ?? null,
            'uuid'        =>(string)Str::orderedUuid(),
        ]);

        return $this->getConsentId($token,$consent);
       // return $consent;
       }else{
        return response()->json(
            [
                'success' => false,
                'error_message' =>"Failed To Get Consent Request",
            ], 500
        );
       }
       
    }

    public function getConsentId($token,$consent){
        $response = Http::withHeaders([
            'Authorization' => 'DirectLogintoken='.$token.'',
            'Cookie'        => env('COOKIE'),
            'Content-Type'  => 'application/json',
        ])->post(env('BASE_URL').'/'."obp/v5.1.0/consumer/consent-requests/".$consent->consent_request_id."/IMPLICIT/consents");

        if ($response['consent_id']) {
           $consent->consent_id =$response['consent_id'];
           $consent->jwt =$response['jwt'];
           $consent->status =$response['status'];
           $consent->view_id    =$response['account_access']['view_id'];
           $consent->counterparty_id =$response['account_access']['helper_info']['counterparty_id'];
           $consent->save();

           return $this->verifyConsentId($token,$consent);
        }else{
            return response()->json(
                [
                    'success' => false,
                    'error_message' =>"Failed To Get Consent ID",
                ], 500
            );
        }

       // return $response;
    }

    public function verifyConsentId($token,$consent){
        $response = Http::withHeaders([
            'Authorization' => 'DirectLogintoken='.$token.'',
            'Cookie'        => env('COOKIE'),
            'Content-Type'  => 'application/json',
        ])
        ->withBody(json_encode(['answer' => '78836665']), 'application/json')
        ->post(env('BASE_URL').'/'."obp/v5.1.0/banks/gh.29.uk/consents/".$consent->consent_id."/challenge");
        
        if ($response['consent_id']) {
            $consent->consent_id =$response['consent_id'];
            $consent->jwt =$response['jwt'];
            $consent->status =$response['status'];
            $consent->save();

            return response()->json([
                'success' =>true,
                'message' =>'You have Successfully Link your NMB Account with ELDIZER Finance Ltd',
               ],200
            );

        }else{
            return response()->json(
                [
                    'success' => false,
                    'error_message' =>"Failed To Verify Consent ID",
                ], 500
            );
        }
    }

   



}
