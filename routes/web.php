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
use App\Http\Controllers\Management\DeviceController;
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
Route::get('due-days', [HomeController::class, 'dueDays'])->name('due.days');
Route::post('user/authentication',[LoginController::class,'authentication'])->name('authentication');

Route::group(['middleware'=>'auth'],function(){
    Route::get('logout',[LoginController::class,'logout'])->name('logout');
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('admin/dashboard',[DashboardController::class,'adminDashboardForm'])->name('admin.dashboard');
    Route::post('dashboard/data',[DashboardController::class,'dashboardData'])->name('dashboard.data');
    Route::get('change/password',[LoginController::class,'changePassword'])->name('change.password');
    Route::post('password/change',[LoginController::class,'passwordChange'])->name('password.change');
    Route::get('bar/chart',[DashboardController::class,'barChart'])->name('admin.bar.chart');
    Route::post('bar/charts',[DashboardController::class,'barCharts'])->name('admin.bar.charts');
    Route::get('loan/applications',[LoanApplicationController::class,'index'])->name('loan.applications');
    Route::get('loan/application/profile/{uuid}',[LoanApplicationController::class,'profile'])->name('loan.profile');
    Route::post('reject/loan/application',[LoanApplicationController::class,'rejectApplication'])->name('reject.loan.application');
    Route::post('approve/loan/application',[LoanApplicationController::class,'approveApplication'])->name('approve.loan.application');
    Route::post('agent/approve/loan/application',[LoanApplicationController::class,'agentApproveApplication'])->name('agent.approve.loan.application');
    Route::get('loan/contracts',[LoanContractController::class,'index'])->name('loan.contracts');
    Route::get('loan/contract/profile/{uuid}',[LoanContractController::class,'profile'])->name('loan.contract.profile');
    Route::get('payments/disbursed',[PaymentController::class,'disbursments'])->name('payment.disbursed');
    Route::post('loan/repayment',[PaymentController::class,'loanRepayment'])->name('loan.repayment');
    Route::get('payments',[PaymentController::class,'payments'])->name('payments');
    Route::post('college/update',[UniversityController::class,'collegeUpdate'])->name('update.college');
    Route::post('agent/update',[AgentController::class,'agentUpdate'])->name('update.agent');
    Route::post('user/update',[UserController::class,'userUpdate'])->name('update.user');
    Route::post('user/update/roles',[UserController::class,'userUpdateRoles'])->name('update.user.roles');
    Route::post('college/status',[UniversityController::class,'collegeStatus'])->name('college.status');
    Route::post('user/status',[UserController::class,'userStatus'])->name('user.status');
    Route::post('delete/user',[UserController::class,'destroy'])->name('user.delete');
    Route::post('college/delete',[UniversityController::class,'destroy'])->name('college.delete');
    Route::post('update/customer',[CustomerController::class,'update'])->name('update.customer');
    Route::post('delete/device',[DeviceController::class,'destroyDevice'])->name('device.delete');
    #### Report
    Route::get('generate/contract/report',[LoanContractController::class,'generateExcelReport'])->name('generate.loan.contracts');
    Route::get('generate/customer/report',[CustomerController::class,'generateExcelReport'])->name('genderate.customer.report');
    Route::get('generate/loan/application/report',[LoanApplicationController::class,'generateExcelReport'])->name('genderate.loan.application.report');

    Route::resources([
        'users'          =>UserController::class,
        'colleges'       =>UniversityController::class,
        'agents'         =>AgentController::class,
        'customers'      =>CustomerController::class,
        'devices'        =>DeviceController::class,
    ]);
});
