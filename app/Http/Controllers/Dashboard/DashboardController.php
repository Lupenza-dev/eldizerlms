<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanContract;
use DateTime;
use App\Models\Management\College;
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

    public function barChart(){
        return response()->json([
            'approved_applications' =>$this->approvedApplication(),
            'rejected_applications' =>$this->rejectedApplication(),
            'granted_loans'         =>$this->grantedLoans(),
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
}
