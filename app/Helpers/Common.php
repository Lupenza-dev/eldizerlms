<?php


//check_time

use App\Models\Management\AssignmentParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists('greeting')) {
    function greeting()
    {
        $time = date("H");
        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");
        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            return "Good morning";
        } else
        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            return "Good afternoon";
        } else
        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            return "Good evening";
        } else
        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "19") {
            return "Good evening";
        }
    }
}

if (!function_exists('getCollegeId')) {
    function getCollegeId(){
        return Auth::user()->agent?->college_id;
    }
    # code...
}

if (!function_exists('mobileNotification')) {
    function mobileNotification($token,$message){

        $response =Http::post('https://exp.host/--/api/v2/push/send',[
            'to'=>$token,
            'title'=>'Chuo Credit',
            'body'=>$message,
            'sound' =>'default'
        ]);

        return $response;
    }
}

if (!function_exists('participationStatus')) {
    function participationStatus($assignmentId){
        $return =AssignmentParticipant::where('user_id',Auth::user()->id)
        ->where('assignment_id',$assignmentId)
        ->count();
        return $return ? true : false;
    }
    # code...
}

if (!function_exists('getApiToken')) {
    function getApiToken(){
        try {
            $response =Http::post(env('SOLOCODE_BASE_URL').''.'token',[
                'partnerId'=>env('PARTNER_ID'),
                'password'=>env('PARTNER_PASSWORD'),
            ]);
            Log::info('----api----result');
            Log::info($response);
            return json_decode($response,true);
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return ['success'=>false,'message'=>$th->getMessage()];
        }
      
    }
}






