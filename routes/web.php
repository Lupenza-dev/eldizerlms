<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Management\UserController;
use App\Http\Controllers\Management\UniversityController;
use App\Http\Controllers\Management\AgentController;
use App\Http\Controllers\Management\CustomerController;
use App\Http\Controllers\Loan\LoanApplicationController;
use App\Http\Controllers\Loan\LoanContractController;
use App\Http\Controllers\Payment\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('/');
Route::post('user/autyentication',[LoginController::class,'authentication'])->name('authentication');

Route::group(['middleware'=>'auth'],function(){
    Route::get('logout',[LoginController::class,'logout'])->name('logout');
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('bar/chart',[DashboardController::class,'barChart'])->name('admin.bar.chart');
    Route::get('loan/applications',[LoanApplicationController::class,'index'])->name('loan.applications');
    Route::get('loan/application/profile/{uuid}',[LoanApplicationController::class,'profile'])->name('loan.profile');
    Route::post('reject/loan/application',[LoanApplicationController::class,'rejectApplication'])->name('reject.loan.application');
    Route::post('approve/loan/application',[LoanApplicationController::class,'approveApplication'])->name('approve.loan.application');
    Route::get('loan/contracts',[LoanContractController::class,'index'])->name('loan.contracts');
    Route::get('loan/contract/profile/{uuid}',[LoanContractController::class,'profile'])->name('loan.contract.profile');
    Route::get('payments/disbursed',[PaymentController::class,'disbursments'])->name('payment.disbursed');
    Route::post('loan/repayment',[PaymentController::class,'loanRepayment'])->name('loan.repayment');
    Route::get('payments',[PaymentController::class,'payments'])->name('payments');

    #### Report
    Route::get('generate/contract/report',[LoanContractController::class,'generateExcelReport'])->name('generate.loan.contracts');
    Route::get('generate/customer/report',[CustomerController::class,'generateExcelReport'])->name('genderate.customer.report');

    Route::resources([
        'users'          =>UserController::class,
        'colleges'       =>UniversityController::class,
        'agents'         =>AgentController::class,
        'customers'      =>CustomerController::class,
    ]);
});
