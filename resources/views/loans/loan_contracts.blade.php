@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
</style>
<div class="page-wrapper bg-light">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">
                <h5 class="mb-0 fw-bold text-primary">Loan Contracts</h5>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;" class="text-muted text-decoration-none">
                                <i class="bx bx-home-alt text-primary"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group shadow-sm">
                    <button class="btn btn-info px-3 py-2 shadow-sm" id="filter-btn">
                        <i class="bx bx-filter text-white me-2"></i>
                        <span class="text-white">Filter</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">Loan Contracts</h6>
                    <div class="badge bg-success" id="record-count">
                        {{ $contracts->count() }} Records
                    </div>
                </div>
                
                <form action="" id="filter-form" class="bg-light p-4 rounded-3 mb-4" style="display: none">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $requests['start_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $requests['end_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Loan Code</label>
                            <input type="text" class="form-control" name="contract_code" placeholder="Write Loan Code" value="{{ $requests['contract_code'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="loan_status" class="form-control">
                                <option value="">Choose Status</option>
                                <option value="GRANTED">GRANTED</option>
                                <option value="CLOSED">CLOSED</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Past Due Days</label>
                            <select name="past_due_days" class="form-control">
                                <option value="">Choose Days</option>
                                <option value="0-30">0-30</option>
                                <option value="31-60">31-60</option>
                                <option value="61-90">61-90</option>
                                <option value="90+">90+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="number" class="form-control" name="phone_number" value="{{ $requests['phone_number'] ?? null}}" placeholder="2557*****">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">University</label>
                            <select name="university_id" class="form-control">
                                <option value="">Choose University</option>
                                @foreach ($universities as $item)
                                <option value="{{ $item->id}}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Student Reg ID</label>
                            <input type="text" class="form-control" name="student_reg_id" value="{{ $requests['student_reg_id'] ?? null}}" placeholder="Student Reg ID">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-12 text-center">
                            <button formaction="{{ route('loan.contracts') }}" class="btn btn-primary px-4 py-2 me-2">
                                <i class="bx bx-search me-1"></i> Search
                            </button>
                            @if (Auth::user()->hasRole(['Admin','Super Admin']))
                            <button formaction="{{ route('generate.loan.contracts') }}" class="btn btn-success px-4 py-2">
                                <i class="bx bx-file me-1"></i> Generate Excel
                            </button>
                            @endif
                        </div>
                    </div>
                </form>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Loan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                @endif
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($contracts as $contract)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($contract->start_date)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($contract->expected_end_date)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $contract->customer->first_name.' '.$contract->customer->middle_name.' '.$contract->customer->last_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span>{{ $contract->customer->email }}</span>
                                        <span class="text-gray-500">{{ $contract->customer->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($contract->amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($contract->loan_amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ number_format($contract->current_balance) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-medium">{{ number_format($contract->outstanding_amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{!! $contract->status_formatted !!}</td>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('loan.contract.profile',$contract->uuid)}}" class="text-decoration-none">
                                        <button class="btn btn-primary btn-sm shadow-sm" title="View Profile">
                                            <i class="bx bx-user"></i>
                                        </button>
                                    </a>
                                </td>
                                @endif
                            </tr> 
                        @endforeach
                       </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filter-btn');
    const filterForm = document.getElementById('filter-form');
    
    if (filterBtn && filterForm) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (filterForm.style.display === 'none') {
                filterForm.style.display = 'block';
            } else {
                filterForm.style.display = 'none';
            }
        });
    }
    
    const recordCount = document.getElementById('record-count');
    if(recordCount) {
        const count = {{ $contracts->count() }};
        recordCount.textContent = count + ' Record' + (count !== 1 ? 's' : '');
    }
});
</script>
@endsection
