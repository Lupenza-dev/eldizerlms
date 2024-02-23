<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanContract;
use App\Models\Management\Agent;
use DateTime;
use App\Models\Management\College;
use App\Models\Management\Customer;
use App\Models\Payment\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $filter   =Auth::user()->hasRole('Agent') ? true : false;
        $loan_applications =LoanApplication::where('level','!=','Canceled')
                            ->when($filter,function($query){
                                $query->where('college_id',getCollegeId());
                            })
                            ->get();
        $loan_contracts =LoanContract::when($filter,function($query){
                            $query->where('college_id',getCollegeId());
                        })
        ->get();
        return view('dashboards.admin',compact('loan_applications','loan_contracts'));
    }

    public function adminDashboardForm(){
        $start_date =Carbon::now()->startOfYear()->format('Y-m-d');
        $end_date   =Carbon::now()->format('Y-m-d');
        return view('dashboards.admin_dashboard',compact('start_date','end_date'));
    }

    public function dashboardData(Request $request){
        $start_date =$request->start_date ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $end_date   =$request->end_date ?? Carbon::now()->format('Y-m-d') ;
        return response()->json([
            'customers' =>number_format(Customer::whereBetween('created_at',[$start_date,$end_date])->count()),
            'app_users' =>number_format(User::whereBetween('created_at',[$start_date,$end_date])->count()),
            'colleges'  =>number_format(College::whereBetween('created_at',[$start_date,$end_date])->count()),
            'agents'    =>number_format(Agent::whereBetween('created_at',[$start_date,$end_date])->count()),
            'loan_applications' =>number_format(LoanApplication::whereBetween('start_date',[$start_date,$end_date])->count()),
            'approved_loan_applications' =>number_format(LoanApplication::whereBetween('start_date',[$start_date,$end_date])->where('level','GRANTED')->count()),
            'rejected_applications'      =>number_format(LoanApplication::whereBetween('start_date',[$start_date,$end_date])->whereIn('level',['Rejected by Agent','Rejected by Admin'])->count()),
            'total_contracts'            =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->count()),
            'total_contract_sum'         =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->sum('loan_amount')),
            'total_collected'            =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->sum('current_balance')),
            'outstanding_amount'         =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->sum('outstanding_amount')),
            'active_contracts'           =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->where('status','GRANTED')->sum('outstanding_amount')),
            'disburesed_amount'          =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->sum('amount')),
            'closed_contract_counts'     =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->where('status','CLOSED')->count()),
            'closed_contracts'           =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->where('status','CLOSED')->sum('loan_amount')),
            'expected_interest'          =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->sum('interest_amount')),
            'collected_interest'         =>number_format(LoanContract::whereBetween('start_date',[$start_date,$end_date])->where('status','CLOSED')->sum('interest_amount')),
        ]);

    }

    public function barChart(){
      
        return response()->json([
            'approved_applications' =>$this->approvedApplication(),
            'rejected_applications' =>$this->rejectedApplication(),
            'granted_loans'         =>$this->grantedLoans(),
        ],200);
    }

    public function barCharts(Request $request){
        $start_date  =$request->start_date ?? Carbon::now()->startOfYear()->format('Y-m-d');
        $end_date    =$request->end_date ?? Carbon::now()->format('Y-m-d') ;
        return response()->json([
            'approved_applications' =>$this->approvedApplications($start_date,$end_date),
            'rejected_applications' =>$this->rejectedApplications($start_date,$end_date),
            'granted_loans'         =>$this->grantedLoanContracts($start_date,$end_date),
            'payment_collections'   =>$this->paymentCollections($start_date,$end_date),
        ],200);
    }

    public function approvedApplication() {
        $filter   =Auth::user()->hasRole('Agent') ? true : false;
        $monthly_sales = LoanApplication::
                         whereYear( 'created_at', date( 'Y' ) )
                        ->where('level','GRANTED')
                        ->when($filter,function($query){
                            $query->where('college_id',getCollegeId());
                        })
                        ->selectRaw('COUNT(id) as count, YEAR(created_at) year,MONTH(created_at) month ' )
                        ->groupBy('year', 'month' )
                        ->get( array( 'month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);

    }

    public function rejectedApplication(){
        $filter   =Auth::user()->hasRole('Agent') ? true : false;
        $monthly_sales = LoanApplication::
                        whereYear( 'created_at', date( 'Y' ) )
                        ->whereIn('level',['Rejected by Agent','Rejected by Admin'])
                        ->when($filter,function($query){
                            $query->where('college_id',getCollegeId());
                        })
                        ->selectRaw('COUNT(id) as count, YEAR(created_at) year,MONTH(created_at) month ')
                        ->groupBy('year','month' )
                        ->get( array('month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);
    }

    public function grantedLoans(){
        $filter   =Auth::user()->hasRole('Agent') ? true : false;
        $monthly_sales = LoanContract::
                        whereYear( 'start_date', 2021 )
                        //->where('status','Rejected')
                        ->when($filter,function($query){
                            $query->where('college_id',getCollegeId());
                        })
                        ->selectRaw('COUNT(id) as count, YEAR(start_date) year,MONTH(start_date) month ')
                        ->groupBy('year','month' )
                        ->get( array('month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);
    }

    public function approvedApplications($start_date,$end_date) {
        $monthly_sales = LoanApplication::
                         whereBetween('start_date',[$start_date,$end_date])
                        ->whereIn('level',['GRANTED','Approved by Agent'])
                        ->selectRaw('COUNT(id) as count, YEAR(start_date) year,MONTH(start_date) month ' )
                        ->groupBy('year', 'month' )
                        ->get( array( 'month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);

    }

    public function rejectedApplications($start_date,$end_date){
        $monthly_sales = LoanApplication::
                         whereBetween('start_date',[$start_date,$end_date])
                        ->whereIn('level',['Rejected by Agent','Rejected by Admin'])
                        ->selectRaw('COUNT(id) as count, YEAR(start_date) year,MONTH(start_date) month ')
                        ->groupBy('year','month' )
                        ->get( array('month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);
    }

    public function grantedLoanContracts($start_date,$end_date){
        $monthly_sales = LoanContract::
                        whereBetween('start_date',[$start_date,$end_date])
                        ->selectRaw('SUM(amount) as count, YEAR(start_date) year,MONTH(start_date) month ')
                        ->groupBy('year','month' )
                        ->get( array('month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);
    }

    public function paymentCollections($start_date,$end_date){
        $monthly_sales = Payment::
                        whereBetween('payment_date',[$start_date,$end_date])
                        ->selectRaw('SUM(amount) as count, YEAR(payment_date) year,MONTH(payment_date) month ')
                        ->groupBy('year','month' )
                        ->get( array('month', 'count' ));

        $sales_array = array();
        foreach ( $monthly_sales as $sales ) {

            //$months = array( 1, 2, 3, 4, 5, 6 )
            $dateObj   = DateTime::createFromFormat( '!m', $sales->month );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );
            $sale_ = $sales->count;

            $sales_array[$monthName] = $sales->count;

        }

        $month_array = array();
        for ( $i = 1; $i <= 12; $i++ ) {

            $dateObj   = DateTime::createFromFormat( '!m', $i );
            $monthName = substr( $dateObj->format( 'F' ), 0, 3 );

            if ( array_key_exists( $monthName, $sales_array ) ) {
                $month_array[] = $sales_array[$monthName];
            } else {
                $month_array[] = 0;
            }

        }
       // return implode( ',', $month_array );

        return json_encode($month_array);
    }

    
}
