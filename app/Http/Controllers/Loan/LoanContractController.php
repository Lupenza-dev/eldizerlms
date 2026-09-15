<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan\LoanContract;
use App\Models\Management\College;
use App\Models\Management\Hospital;
use App\Traits\LoanTrait;
use Illuminate\Support\Facades\Auth;

class LoanContractController extends Controller
{
    use LoanTrait;

    public function index(Request $request)
    {
        $requests  = $request->all();
        $filter   = Auth::user()->hasRole('Agent') ? true : false;

        $contracts = LoanContract::with('customer', 'student', 'customer.intern', 'customer.intern.hospital','loan_application','loan_application.customer_bank_detail')
            ->orderBy('start_date', 'DESC')
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->whereHas('customer', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->when($this->hasStudentFilters($requests), function ($query) use ($requests) {
                $query->where(function ($q) use ($requests) {
                    $q->whereHas('student', function ($q2) use ($requests) {
                        $q2->withfilters($requests);
                    })->orWhereDoesntHave('student');
                });
            })
            ->when($requests['hospital_id'] ?? null, function ($query) use ($requests) {
                $query->whereHas('customer.intern', function ($q) use ($requests) {
                    $q->where('hospital_id', $requests['hospital_id']);
                });
            })
            ->when($requests['bank'] ?? null, function ($query) use ($requests) {
                $query->whereHas('loan_application.customer_bank_detail', function ($q) use ($requests) {
                    $q->where('bank_name', $requests['bank']);
                });
            })
            ->when($filter, function ($query) {
                $query->where('college_id', getCollegeId());
            })
            ->get();
        $universities = College::latest()->get();
        $hospitals = Hospital::latest()->get();
        return view('loans.loan_contracts', compact('contracts', 'universities', 'hospitals', 'requests'));
    }

    private function hasStudentFilters($requests)
    {
        // adjust keys to whatever withFilters() actually looks at for the student relation
        return isset($requests['college_id']) || isset($requests['college_id']) /* etc */;
    }

    public function profile($uuid)
    {
        $contract = LoanContract::with('customer', 'loan_approval', 'installments', 'payments', 'guarantors', 'customer_mandate')->where('uuid', $uuid)->first();
        return view('loans.loan_contract_profile', compact('contract'));
    }

    public function generateExcelReport(Request $request)
    {
        $requests  = $request->all();
        $filter   = Auth::user()->hasRole('Agent') ? true : false;
        $contracts = LoanContract::with('customer', 'student', 'customer.intern', 'customer.intern.hospital','loan_application','loan_application.customer_bank_detail')
            ->orderBy('start_date', 'DESC')
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->whereHas('customer', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->when($this->hasStudentFilters($requests), function ($query) use ($requests) {
                $query->where(function ($q) use ($requests) {
                    $q->whereHas('student', function ($q2) use ($requests) {
                        $q2->withfilters($requests);
                    })->orWhereDoesntHave('student');
                });
            })
            ->when($requests['hospital_id'] ?? null, function ($query) use ($requests) {
                $query->whereHas('customer.intern', function ($q) use ($requests) {
                    $q->where('hospital_id', $requests['hospital_id']);
                });
            })
            ->when($requests['bank'] ?? null, function ($query) use ($requests) {
                $query->whereHas('loan_application.customer_bank_detail', function ($q) use ($requests) {
                    $q->where('bank_name', $requests['bank']);
                });
            })
            ->when($filter, function ($query) {
                $query->where('college_id', getCollegeId());
            })
            ->cursor();

        return self::exportLoanReport($contracts);
    }
}
