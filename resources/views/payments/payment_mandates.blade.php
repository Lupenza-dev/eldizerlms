@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
    .divider{
        margin-top: 10px !important;
    }
    label{
        margin-bottom: 5px !important;
    }
</style>
<div class="page-wrapper bg-light">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">
                <h5 class="mb-0 fw-bold text-primary">Payment Mandates</h5>
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
                    <a href="{{ route('sync.mandate')}}" class="text-decoration-none">
                        <button type="button" class="btn btn-primary px-4 py-2">
                            <i class="bx bx-refresh me-2"></i> Sync Mandates
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">All Payment Mandates</h6>
                    <div class="badge bg-success" id="record-count">
                        {{ $payments->count() }} Records
                    </div>
                </div>
                <hr class="my-3"/>
                <div class="table-responsive mt-4">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mandate Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outstanding Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($payment->start_date)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($payment->end_date)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $payment->reference }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $payment->customer_name ?? "Name" }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($payment->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ number_format($payment->paid_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-medium">{{ number_format($payment->outstanding_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('view.payment.mandate',$payment->reference)}}">
                                        <button class="btn btn-sm btn-primary">View</button>
                                    </a>
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
// Update record count dynamically
document.addEventListener('DOMContentLoaded', function() {
    const recordCount = document.getElementById('record-count');
    if(recordCount) {
        const count = {{ $payments->count() }};
        recordCount.textContent = count + ' Record' + (count !== 1 ? 's' : '');
    }
});
</script>
@endsection
