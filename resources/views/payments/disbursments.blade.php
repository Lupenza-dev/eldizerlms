@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Payment Disbursements</span>
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
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $payments->count() }} Records
                </span>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="bx bx-send text-white text-xl"></i>
                    </div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Payment Disbursements</h6>
                </div>
                <button class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="filter-btn">
                    <i class="bx bx-filter text-lg"></i> Filter
                </button>
            </div>

            {{-- Filter Panel --}}
            <form action="" id="filter-form" class="border-b border-slate-200 bg-slate-50 px-6 py-5" style="display:none">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Start Date</label>
                        <input type="date" class="form-control rounded-lg text-sm" name="start_date" value="{{ $requests['start_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">End Date</label>
                        <input type="date" class="form-control rounded-lg text-sm" name="end_date" value="{{ $requests['end_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment Method</label>
                        <select name="payment_method" class="form-control rounded-lg text-sm">
                            <option value="">Choose Method</option>
                            <option value="BANK">Bank Transfer</option>
                            <option value="MOBILE">Mobile Money</option>
                            <option value="CASH">Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment Channel</label>
                        <select name="payment_channel" class="form-control rounded-lg text-sm">
                            <option value="">Choose Channel</option>
                            <option value="NMB">NMB Bank</option>
                            <option value="TIGO">Tigo Pesa</option>
                            <option value="M-PESA">M-Pesa</option>
                            <option value="AIRTEL">Airtel Money</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment Reference</label>
                        <input type="text" class="form-control rounded-lg text-sm" name="payment_reference" placeholder="Payment Reference" value="{{ $requests['payment_reference'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="number" class="form-control rounded-lg text-sm" name="phone_number" value="{{ $requests['phone_number'] ?? null}}" placeholder="2557*****">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Customer Name</label>
                        <input type="text" class="form-control rounded-lg text-sm" name="customer_name" value="{{ $requests['customer_name'] ?? null}}" placeholder="Customer Name">
                    </div>
                    <div class="flex items-end justify-end gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                            <i class="bx bx-search"></i> Search
                        </button>
                        <button type="reset" class="inline-flex items-center gap-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                            <i class="bx bx-x"></i> Clear
                        </button>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Payment Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reference</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Channel</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $payment)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($payment->payment_date)) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $payment->loan_contract->customer->first_name.' '.$payment->loan_contract->customer->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    <div class="flex flex-col gap-0.5">
                                        <span>{{ $payment->loan_contract->customer->email }}</span>
                                        <span class="text-slate-400 text-xs">{{ $payment->loan_contract->customer->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($payment->paid_amount, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $payment->payment_reference }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @switch($payment->payment_method)
                                        @case('BANK') <span class="badge bg-primary">Bank Transfer</span> @break
                                        @case('MOBILE') <span class="badge bg-success">Mobile Money</span> @break
                                        @case('CASH') <span class="badge bg-warning text-dark">Cash</span> @break
                                        @default <span class="badge bg-secondary">{{ $payment->payment_method }}</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @switch($payment->payment_channel)
                                        @case('NMB') <span class="badge bg-info">NMB Bank</span> @break
                                        @case('TIGO') <span class="badge bg-success">Tigo Pesa</span> @break
                                        @case('M-PESA') <span class="badge bg-primary">M-Pesa</span> @break
                                        @case('AIRTEL') <span class="badge bg-danger">Airtel Money</span> @break
                                        @default <span class="badge bg-secondary">{{ $payment->payment_channel }}</span>
                                    @endswitch
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
            filterForm.style.display = filterForm.style.display === 'none' ? 'block' : 'none';
        });
    }
    const recordCount = document.getElementById('record-count');
    if (recordCount) {
        const count = {{ $payments->count() }};
        recordCount.textContent = count + ' Record' + (count !== 1 ? 's' : '');
    }
});
</script>
@endsection

