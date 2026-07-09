@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Loan Contracts</span>
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
                <button class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="filter-btn">
                    <i class="bx bx-filter text-lg"></i> Filter
                </button>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="bx bx-receipt text-white text-xl"></i>
                    </div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Loan Contracts</h6>
                </div>
                <span class="bg-emerald-500/20 text-emerald-300 text-xs font-semibold px-3 py-1 rounded-full" id="record-count">
                    {{ $contracts->count() }} Records
                </span>
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
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Loan Code</label>
                        <input type="text" class="form-control rounded-lg text-sm" name="contract_code" placeholder="Write Loan Code" value="{{ $requests['contract_code'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="loan_status" class="form-control rounded-lg text-sm">
                            <option value="">Choose Status</option>
                            <option value="GRANTED">GRANTED</option>
                            <option value="CLOSED">CLOSED</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Past Due Days</label>
                        <select name="past_due_days" class="form-control rounded-lg text-sm">
                            <option value="">Choose Days</option>
                            <option value="0-30">0-30</option>
                            <option value="31-60">31-60</option>
                            <option value="61-90">61-90</option>
                            <option value="90+">90+</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="number" class="form-control rounded-lg text-sm" name="phone_number" value="{{ $requests['phone_number'] ?? null}}" placeholder="2557*****">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">University</label>
                        <select name="university_id" class="form-control rounded-lg text-sm">
                            <option value="">Choose University</option>
                            @foreach ($universities as $item)
                            <option value="{{ $item->id}}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Reg ID</label>
                        <input type="text" class="form-control rounded-lg text-sm" name="student_reg_id" value="{{ $requests['student_reg_id'] ?? null}}" placeholder="Student Reg ID">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-200">
                    <button formaction="{{ route('loan.contracts') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                        <i class="bx bx-search"></i> Search
                    </button>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <button formaction="{{ route('generate.loan.contracts') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                        <i class="bx bx-file"></i> Generate Excel
                    </button>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Start Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">End Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Total Loan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Paid</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Outstanding</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($contracts as $contract)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $contract->start_date ? date('d M Y', strtotime($contract->start_date)) : 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $contract->expected_end_date ? date('d M Y', strtotime($contract->expected_end_date)) : 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ ($contract->customer?->first_name ?? '').' '.($contract->customer?->middle_name ?? '').' '.($contract->customer?->last_name ?? '') }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    <div class="flex flex-col gap-0.5">
                                        <span>{{ $contract->customer?->email ?? 'N/A' }}</span>
                                        <span class="text-slate-400 text-xs">{{ $contract->customer?->phone_number ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($contract->amount) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($contract->loan_amount) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-emerald-600 whitespace-nowrap">{{ number_format($contract->current_balance) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-orange-500 whitespace-nowrap">{{ number_format($contract->outstanding_amount) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{!! $contract->status_formatted !!}</td>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('loan.contract.profile',$contract->uuid)}}" class="text-decoration-none">
                                        <button class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors" title="View Profile">
                                            <i class="bx bx-user text-sm"></i> View
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
            filterForm.style.display = filterForm.style.display === 'none' ? 'block' : 'none';
        });
    }
    const recordCount = document.getElementById('record-count');
    if (recordCount) {
        const count = {{ $contracts->count() }};
        recordCount.textContent = count + ' Record' + (count !== 1 ? 's' : '');
    }
});
</script>
@endsection
