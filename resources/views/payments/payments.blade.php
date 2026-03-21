@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
</style>
<div class="page-wrapper bg-light">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">
                <h5 class="mb-0 fw-bold text-primary">Repayments</h5>
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
                    <div class="badge bg-success" id="record-count">
                        {{ $payments->count() }} Records
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">Loan Repayments</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-info px-3 py-2 shadow-sm" id="filter-btn">
                            <i class="bx bx-filter text-white me-2"></i>
                            <span class="text-white">Filter</span>
                        </button>
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
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="">Choose Method</option>
                                <option value="BANK">Bank Transfer</option>
                                <option value="MOBILE">Mobile Money</option>
                                <option value="CASH">Cash</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Payment Channel</label>
                            <select name="payment_channel" class="form-control">
                                <option value="">Choose Channel</option>
                                <option value="NMB">NMB Bank</option>
                                <option value="TIGO">Tigo Pesa</option>
                                <option value="M-PESA">M-Pesa</option>
                                <option value="AIRTEL">Airtel Money</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Payment Reference</label>
                            <input type="text" class="form-control" name="payment_reference" placeholder="Payment Reference" value="{{ $requests['payment_reference'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="number" class="form-control" name="phone_number" value="{{ $requests['phone_number'] ?? null}}" placeholder="2557*****">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" class="form-control" name="customer_name" value="{{ $requests['customer_name'] ?? null}}" placeholder="Customer Name">
                        </div>
                        <div class="col-md-3 d-flex align-items-end justify-content-end gap-2">
                            <button class="btn btn-primary px-3 py-2" type="submit">
                                <i class="bx bx-search me-1"></i> Search
                            </button>
                            <button class="btn btn-secondary px-3 py-2" type="reset">
                                <i class="bx bx-x me-1"></i> Clear
                            </button>
                        </div>
                    </div>
                </form>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Channel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($payment->payment_date)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $payment->loan_contract?->customer->first_name.' '.$payment->loan_contract?->customer->last_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span>{{ $payment->loan_contract?->customer->email }}</span>
                                        <span class="text-gray-500">{{ $payment->loan_contract?->customer->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $payment->payment_reference }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($payment->payment_method)
                                        @case('BANK')
                                            <span class="badge bg-primary">Bank Transfer</span>
                                            @break
                                        @case('MOBILE')
                                            <span class="badge bg-success">Mobile Money</span>
                                            @break
                                        @case('CASH')
                                            <span class="badge bg-warning text-dark">Cash</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($payment->payment_channel)
                                        @case('NMB')
                                            <span class="badge bg-info">NMB Bank</span>
                                            @break
                                        @case('TIGO')
                                            <span class="badge bg-success">Tigo Pesa</span>
                                            @break
                                        @case('M-PESA')
                                            <span class="badge bg-primary">M-Pesa</span>
                                            @break
                                        @case('AIRTEL')
                                            <span class="badge bg-danger">Airtel Money</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $payment->payment_channel }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $payment->remarks ?? 'N/A' }}">
                                        {{ $payment->remarks ?? 'N/A' }}
                                    </span>
                                </td>
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
        const count = {{ $payments->count() }};
        recordCount.textContent = count + ' Record' + (count !== 1 ? 's' : '');
    }
});
</script>
@endsection

