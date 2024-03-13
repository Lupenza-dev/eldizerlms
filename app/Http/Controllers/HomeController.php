<?php

namespace App\Http\Controllers;

use App\Jobs\LoanPenaltCalculation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Entities\Region;
use App\Models\Entities\District;
use App\Models\Management\Customer;
use App\Models\Management\College;
use App\Models\Management\Student;
use App\Models\Loan\LoanApplication;
use App\Services\Loan\LoanCalculatorService;
use App\Models\Loan\LoanContract;
use App\Models\Loan\Installment;
use App\Models\Payment\DisbursmentPayment;
use App\Models\Payment\Payment;
use Str;
use DateTimeImmutable;
use DateTimeZone;
use Carbon\Carbon;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }

    public function dueDays(){
        LoanPenaltCalculation::dispatch()->onQueue('emails');
        return true;
    }

    public function uploadLoans(Request $request){
        $file =$request->file;
        return (new FastExcel)->import($file, function ($row) {
            $first_name     =$row['First Name'];
            $middle_name    =$row['Middle Name'];
            $last_name      =$row['Last Name'];
            $phone_number   =$row['Phone Number'];
            $dob            =$row['DOB'];
            $id_type        =$row['ID Type'];
            $id_number      =$row['ID Number'];
            $gender         =$row['Gender'];
            $region         =$row['Region'];
            $district       =$row['Disrict'];
            $street         =$row['Street'];
            $college_name   =$row['College'];
            $study_year     =$row['Study Year'];
            $reg_number     =$row['Reg ID'];
            $heslb_status   =$row['Heslb Status'];
            $course         =$row['Course'];
            $amount         =$row['Request Amount'];
            $start_date     =$row['start date'];
            $end_date       =$row['end date'];
            $date = Carbon::parse($start_date);
            $endd_date = Carbon::parse($end_date);
            $dob_date = Carbon::parse($dob);

            // Get the date part as a string (YYYY-MM-DD)
            $start_date = $date->toDateString();
            $end_date = $endd_date->toDateString();
            $dob = $dob_date->toDateString();

            ### create customer
            $code =255;
            $phone_number =$code.''.$phone_number;
            $customer =Customer::where('phone_number',$phone_number)->first();

            if (!$customer) {
                $code =255;
                $customer =Customer::create([
                    'first_name'   =>$first_name,
                    'middle_name'  =>$middle_name,
                    'last_name'    =>$last_name,
                    'other_name'   =>null,
                    'phone_number' =>$code.''.$phone_number,
                    'email'        =>null,
                    'id_number'    =>$id_number ?? null,
                    'dob'          =>$dob ?? "2000-01-01",
                    'gender_id'    =>$gender == "ME" ? 1:2,
                    'marital_status_id'    =>null,
                    'region_id'            =>7,
                    'district_id'          =>58,
                    'ward_id'                  =>null,
                    'street'                   =>null,
                    'resident_since'           =>null,
                    'other_name'               =>null,
                    'uuid'                     =>(string)Str::orderedUuid()
                ]);
            }

            ### create student details
            ### create college first before student details

            $college =College::where('name','like','%'.$college_name.'%')->first();

            if (!$college) {
                $college =College::create([
                    'name'       =>ucwords($college_name),
                    'location'   =>$customer->region_id,
                    'uuid'       =>(string)Str::orderedUuid(),
                    'logo'       =>null,
                    'created_by' =>1
                ]);
            }

            $student =Student::where('customer_id',$customer->id)->first();

            if (!$student) {
                $student =Student::create([
                    'customer_id'     =>$customer->id,
                    'college_id'      =>$college->id,
                   // 'position'     =>$valid_data['position'] ??,
                    'study_year'      =>$study_year ?? 1,
                    'student_reg_id'  =>$reg_number,
                    'heslb_status'    =>$heslb_status,
                    'course'          =>$course,
                    'uuid'            =>(string)Str::orderedUuid(),
                ]);
            }

            ### create loan application
            $code_generator =new LoanCalculatorService;

            // check if loan contract exist 

            $check_loan =LoanContract::where('customer_id',$customer->id)
                         ->where('start_date',$start_date)
                         ->where('amount',$amount)
                         ->first();

            if (!$check_loan) {
                $loan_application =LoanApplication::create([
                    'customer_id' =>$customer->id,
                    'amount'      =>$amount,
                    'loan_amount' =>$amount*1.25,
                    'plan'        =>1,
                    'installment_amount'  =>$amount*1.25,
                    'interest_rate'       =>0.25,
                    'interest_amount'     =>0.25 * $amount,
                    'loan_code'           =>$code_generator->loanCode(),
                    'uuid'                =>(string)Str::orderedUuid(),
                    'start_date'          =>$start_date,
                    'level'               =>"CLOSED",
                    'college_id'          =>$college->id,
                    
                ]);

                 ### create loan contract

            $loan_contract =LoanContract::create([
                'customer_id' =>$customer->id,
                'loan_application_id'      =>$loan_application->id,
                'college_id'               =>$loan_application->college_id,
                'amount'                   =>$loan_application->amount,
                'loan_amount'              =>$loan_application->loan_amount,
                'installment_amount'       =>$loan_application->installment_amount,
                'plan'                     =>$loan_application->plan,
                'status'                    =>"CLOSED",
                'current_balance'           =>$loan_application->loan_amount,
                'outstanding_amount'        =>0,
                'contract_code'             =>$loan_application->loan_code,
                'interest_rate'             =>$loan_application->interest_rate,
                'interest_amount'           =>$loan_application->interest_amount,
                'fees_amount'               =>$loan_application->fees_amount,
                'start_date'                =>$start_date,
               // 'expected_end_date'         =>date('Y-m-d', strtotime("+".$loan_application->plan." months", strtotime($loan_application->start_date))),
                'expected_end_date'         =>$end_date,
                'created_by'                =>1,
                'uuid'                      =>(string)Str::orderedUuid(),
               ]);

               $i =1;
               $installment =Installment::create([
                   'loan_contract_id' =>$loan_contract->id,
                   'installment_order' =>$i,
                   'total_amount'      =>$loan_contract->installment_amount,
                   'current_balance'   =>$loan_contract->current_balance,
                   'outstanding_amount' =>0,
                   'payment_type'       =>"Installment Amount",
                  // 'payment_date'       =>date('Y-m-d', strtotime("+".$i." months", strtotime($loan_contract->start_date))),
                   'payment_date'       =>$loan_contract->expected_end_date,
                   'last_paid_amount'   =>$loan_contract->current_balance,
                   'status'             =>"CLOSED",
                   'uuid'               =>(string)Str::orderedUuid(),
               ]);
            // }

              $payment =DisbursmentPayment::create([
               'loan_contract_id'    =>$loan_contract->id,
               'loan_application_id' =>$loan_contract->loan_application_id,
               'payment_reference'   =>$loan_contract->contract_code,
               'paid_amount'         =>$loan_contract->amount,
               'payment_date'        =>$loan_contract->start_date,
               'payment_channel'     =>"NMB",
               'payment_method'      =>"BANK",
               'created_by'          =>1,
               'uuid'                =>(string)Str::orderedUuid(),
              ]);

              $payment =Payment::where('payment_reference',$loan_contract->contract_code)->first();

              if (!$payment) {
                  $payment =Payment::create([
                      'phone_number' =>$loan_contract->customer->phone_number,
                      'amount'       =>$loan_contract->loan_amount,
                      'payment_reference'     =>$loan_contract->contract_code,
                      'payment_channel'       =>"NMB",
                      'payment_method'        =>"BANK",
                      'payment_date'          =>$loan_contract->expected_end_date,
                      'added_by'              =>1,
                      'uuid'                  =>(string)Str::orderedUuid(),
                      'status'                 =>"Successfully",
                      'remarks'               =>'Loan Repayment',
                      'loan_contract_id'      =>$loan_contract->id,
                      'customer_id'           =>$loan_contract->customer_id,
                  ]);
              }

            $details['message'] ='Action done successfuly';
            
            return $details;
            }

           


           
         });
 
         $details['success'] =1;
         $details['message'] ='Action done successfuly';
         
         return $details;
    }

}
