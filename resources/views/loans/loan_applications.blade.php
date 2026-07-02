@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Loan Applications</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
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
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Loan Applications</h6>
                </div>
                <button class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="filter-btn">
                    <i class="bx bx-filter text-lg"></i> Custom Filter
                </button>
            </div>

            {{-- Filter Panel --}}
            <form action="" id="submit-form" class="border-b border-slate-200 bg-slate-50 px-6 py-5" style="display:none">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Start Date</label>
                        <input type="date" name="application_start_date" class="form-control rounded-lg text-sm" value="{{ $requests['application_start_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">End Date</label>
                        <input type="date" name="application_end_date" class="form-control rounded-lg text-sm" value="{{ $requests['application_end_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Gender</label>
                        <select name="gender_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose Gender</option>
                            @if ($requests['gender_id'] ?? null)
                            <option value="1" {{ ($requests['gender_id'] == 1) ? "selected": null}}>Male</option>
                            <option value="2" {{ ($requests['gender_id'] == 2) ? "selected": null}}>Female</option>
                            @else
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                            @endif
                        </select>
                    </div>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                        <select name="college_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose College</option>
                            @foreach ($colleges as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="number" name="phone_number" class="form-control rounded-lg text-sm" value="{{ $requests['phone_number'] ?? null}}" placeholder="255*******">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">ID Number</label>
                        <input type="number" name="id_number" class="form-control rounded-lg text-sm" value="{{ $requests['id_number'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Reg ID</label>
                        <input type="text" name="student_reg_id" class="form-control rounded-lg text-sm" value="{{ $requests['student_reg_id'] ?? null}}">
                    </div>
                    <div class="flex items-end justify-end gap-2">
                        <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" formaction="{{ route('loan.applications')}}" type="submit">
                            <i class="bx bx-search"></i> Search
                        </button>
                        @if (Auth::user()->hasRole(['Admin','Super Admin']))
                        <button class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" formaction="{{ route('genderate.loan.application.report')}}">
                            <i class="bx bx-file"></i> Generate
                        </button>
                        @endif
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
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">App. Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Total Loan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Installment</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Loan Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($loans as $loan)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($loan->created_at)) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $loan->customer->first_name.' '.$loan->customer->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    <div class="flex flex-col gap-0.5">
                                        <span>{{ $loan->customer->email }}</span>
                                        <span class="text-slate-400 text-xs">{{ $loan->customer->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($loan->amount) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($loan->loan_amount) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ number_format($loan->installment_amount) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $loan->plan }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    <div class="flex flex-col gap-0.5">
                                        <span>{!! $loan->loan_type_format !!}</span>
                                        <span class="text-slate-400 text-xs">{{ $loan->get_device?->name }}</span>
                                        <span class="text-xs font-semibold text-slate-600">ID: {{ number_format($loan->initial_deposit) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{!! $loan->level_formatted !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('loan.profile',$loan->uuid)}}" class="text-decoration-none">
                                        <button class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors" title="View Profile">
                                            <i class="bx bx-user text-sm"></i> View
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
    const filterBtn = document.getElementById('filter-btn');
    const submitForm = document.getElementById('submit-form');
    if (filterBtn && submitForm) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            submitForm.style.display = submitForm.style.display === 'none' ? 'block' : 'none';
        });
    }
});
</script>
@endsection

