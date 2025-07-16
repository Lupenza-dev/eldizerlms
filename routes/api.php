<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Mobile\V1\CustomerController;
use App\Http\Controllers\Api\Mobile\V1\LoanApplicationController;
use App\Http\Controllers\Api\Mobile\V1\AuthController;
use App\Http\Controllers\Api\Mobile\V1\HomeController;
use App\Http\Controllers\Api\Mobile\V2\NmbController;
use App\Http\Controllers\Api\Mobile\V2\RegistrationController;
use App\Http\Controllers\HomeController as TestController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('upload-loans',[TestController::class,'uploadLoans']);
Route::get('verify-vrp',[NmbController::class,'verifyVrp']);
Route::get('get-assignments',[HomeController::class,'getAssignments']);

Route::post('V1/user-authentication',[AuthController::class,'userLogin']);
Route::post('V1/recover-password',[AuthController::class,'recoverPassword']);
Route::post('V1/reset-password',[AuthController::class,'resetPassword']);
Route::post('V1/customer-registration',[CustomerController::class,'store']);
Route::post('V1/loan-calculator',[LoanApplicationController::class,'loanCalculator']);
Route::get('V1/get-colleges',[HomeController::class,'getColleges']);
Route::get('V1/get-regions',[HomeController::class,'getRegions']);
Route::get('V1/get-districts/{region_id}',[HomeController::class,'getDistricts']);
Route::get('V1/get-wards/{district_id}',[HomeController::class,'getWards']);
Route::get('V1/get-terms',[HomeController::class,'getTerms']);
Route::post('V1/delete-user',[HomeController::class,'deleteUser']);
Route::group(['prefix'=>'V1','middleware'=>'auth:api'], function(){
    Route::get('get-devices',[HomeController::class,'getDevices']);
    Route::post('student-registration',[CustomerController::class,'storeStudent']);
    Route::post('loan-application',[LoanApplicationController::class,'loanApplication']);
    Route::post('loan-calculator',[LoanApplicationController::class,'loanCalculator']);
    Route::post('get-loans',[HomeController::class,'getLoans']);
    Route::post('get-payments',[HomeController::class,'getPayments']);
    Route::get('get-agents',[HomeController::class,'getAgents']);
    Route::post('change-password',[AuthController::class,'changePassword']);
});

Route::group(['prefix'=>'V2'], function(){
    Route::post('user-authentication',[AuthController::class,'userLogin']);
    Route::post('user-registration',[RegistrationController::class,'registerUser']);
    Route::post('user-registration-address',[RegistrationController::class,'registerUserAddress']);
    Route::post('user-registration-college',[RegistrationController::class,'registerUserCollege']);
    Route::post('user-registration-image',[RegistrationController::class,'registerUserImage']);
    Route::post('subscribe',[NmbController::class,'subscribe']);
    Route::get('get-colleges',[HomeController::class,'getColleges']);
    Route::get('get-regions',[HomeController::class,'getRegions']);
    Route::get('get-assignments',[HomeController::class,'getAssignments']);
    Route::get('get-groups-ads',[HomeController::class,'getGroups']);
    Route::get('get-adverts',[HomeController::class,'getAdverts']);
    Route::get('get-districts/{region_id}',[HomeController::class,'getDistricts']);
    Route::get('get-wards/{district_id}',[HomeController::class,'getWards']);
    Route::group(['middleware'=>'auth:api'], function(){
        Route::get('get-devices',[HomeController::class,'getDevices']);
        Route::post('student-registration',[CustomerController::class,'storeStudent']);
        Route::post('loan-application',[LoanApplicationController::class,'loanApplication']);
        Route::post('loan-calculator',[LoanApplicationController::class,'loanCalculator']);
        Route::post('get-loans',[HomeController::class,'getLoans']);
        Route::post('get-payments',[HomeController::class,'getPayments']);
        Route::get('get-agents',[HomeController::class,'getAgents']);
        Route::post('change-password',[AuthController::class,'changePassword']);
        Route::post('complete-registration',[RegistrationController::class,'completeRegistration']);
      
    });
  
});
