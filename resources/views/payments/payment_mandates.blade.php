@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Payment Mandates</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('sync.mandate')}}" class="text-decoration-none">
                    <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                        <i class="bx bx-refresh"></i> Sync Mandates
                    </button>
                </a>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="bx bx-file text-white text-xl"></i>
                    </div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">All Payment Mandates</h6>
                </div>
                <span class="bg-emerald-500/20 text-emerald-300 text-xs font-semibold px-3 py-1 rounded-full" id="record-count">
                    {{ $payments->count() }} Records
                </span>
            </div>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Start Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">End Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reference</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Total Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Paid</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Outstanding</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $payment)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($payment->start_date)) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($payment->end_date)) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $payment->reference }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $payment->customer_mandate?->customer?->customer_name ?? "N/A" }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($payment->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-emerald-600 whitespace-nowrap">{{ number_format($payment->paid_amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-orange-500 whitespace-nowrap">{{ number_format($payment->outstanding_amount, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('view.payment.mandate',$payment->reference)}}" class="text-decoration-none">
                                        <button class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="bx bx-show text-sm"></i> View
                                        </button>
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
document.addEventListener('DOMContentLoaded', function() {
    const recordCount = document.getElementById('record-count');
    if (recordCount) {
        const count = {{ $payments->count() }};
        recordCount.textContent = count + ' Record' + (count !== 1 ? 's' : '');
    }
});
</script>
@endsection
