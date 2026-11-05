@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">

        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">{{ $page_title ?? 'Loan Applications' }}</span>
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
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">{{ $page_title ?? 'Loan Applications' }}</h6>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="filter-btn">
                        <i class="bx bx-filter text-lg"></i> Custom Filter
                    </button>
                    <!-- @if (Auth::user()->hasRole(['Admin','Super Admin','Internal User','Agent']))
                    <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#newLoanModal">
                        <i class="bx bx-plus text-lg"></i> New Loan Application
                    </button>
                    @endif -->
                </div>
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
                        <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" formaction="{{ $search_route ?? route('loan.applications')}}" type="submit">
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
                        @forelse ($loans as $loan)
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
                                        @if ($loan->loan_type == 2)
                                        <span class="text-slate-400 text-xs">{{ $loan->get_device?->name }}</span>
                                        <span class="text-xs font-semibold text-slate-600">Deposit: {{ number_format($loan->initial_deposit) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{!! $loan->level_formatted !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="btn-group">
                                        <button type="button" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-cog'></i> Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('loan.profile',$loan->uuid)}}">
                                                    <i class='bx bx-user me-2'></i> View
                                                </a>
                                            </li>
                                            @if (!$loan->is_mandate_sent)
                                            <li>
                                                <button type="button" class="dropdown-item confirm-mandate-btn" data-bs-toggle="modal" data-bs-target="#confirmMandateModal"
                                                    data-loan-uuid="{{ $loan->uuid }}"
                                                    data-customer-name="{{ $loan->customer->first_name.' '.$loan->customer->last_name }}"
                                                    data-loan-amount="{{ number_format($loan->loan_amount) }}"
                                                    data-installment-amount="{{ number_format($loan->installment_amount) }}"
                                                    data-created-at="{{ $loan->created_at ? date('Y-m-d', strtotime($loan->created_at)) : '' }}">
                                                    <i class='bx bx-check me-2'></i> Confirm Mandate
                                                </button>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-10 text-center text-sm text-slate-400">
                                    <i class="bx bx-file text-3xl block mb-2"></i>
                                    No loan applications found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Confirm Mandate Modal ════════ --}}
<div class="modal fade" id="confirmMandateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Mandate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="confirm_mandate_form">
                    @csrf
                    <input type="hidden" name="loan_uuid" id="mandate_loan_uuid">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="mandate_customer_name" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Total Loan Amount</label>
                            <input type="text" class="form-control" id="mandate_loan_amount" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Installment Amount</label>
                            <input type="text" class="form-control" id="mandate_installment_amount" readonly>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Created At <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="created_at" id="mandate_created_at" required>
                        </div>
                    </div>
                    <div class="col-md-12" id="confirm_mandate_alert" style="margin-top: 10px"></div>
                    <div class="col-md-12 mt-4" style="text-align:right">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span class="bx bx-x"></span> Close</button>
                        <button type="submit" class="btn btn-success" id="confirm_mandate_btn"><span class="bx bx-check"></span> Confirm Mandate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════ New Loan Application Modal ════════ --}}
<div class="modal fade" id="newLoanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-plus-circle text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">New Loan Application</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-6" style="background:#f8fafc;">
                <form id="new_loan_form">
                    @csrf

                    {{-- Customer Select --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Select Customer <span class="text-red-500">*</span>
                        </label>
                        <select id="customer_select" name="customer_uuid" class="form-control" required style="width:100%"></select>
                        <small class="text-xs text-slate-400 mt-1 block">Search by name, phone, ID, or email</small>
                    </div>

                    {{-- Loan Type Cards --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                            Select Loan Type <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="loan_type" id="loan_type" value="" required>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            {{-- Borrow Money --}}
                            <div class="loan-type-card cursor-pointer rounded-xl border-2 border-slate-200 p-4 transition-all hover:border-blue-400 hover:shadow-md bg-white"
                                 data-loan-type="1">
                                <div class="flex items-start gap-3">
                                    <div class="w-11 h-11 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                        <i class="bx bx-money text-blue-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="font-semibold text-slate-800 text-sm mb-1">Borrow Money</h6>
                                        <p class="text-xs text-slate-500 leading-tight">Cash loan via agent</p>
                                    </div>
                                    <i class="bx bx-check-circle text-blue-600 text-xl hidden card-check"></i>
                                </div>
                            </div>

                            {{-- Device --}}
                            <div class="loan-type-card cursor-pointer rounded-xl border-2 border-slate-200 p-4 transition-all hover:border-emerald-400 hover:shadow-md bg-white"
                                 data-loan-type="2">
                                <div class="flex items-start gap-3">
                                    <div class="w-11 h-11 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                                        <i class="bx bx-mobile-alt text-emerald-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="font-semibold text-slate-800 text-sm mb-1">Device</h6>
                                        <p class="text-xs text-slate-500 leading-tight">Buy device on installment</p>
                                    </div>
                                    <i class="bx bx-check-circle text-emerald-600 text-xl hidden card-check"></i>
                                </div>
                            </div>

                            {{-- Eco Water --}}
                            <div class="loan-type-card cursor-pointer rounded-xl border-2 border-slate-200 p-4 transition-all hover:border-cyan-400 hover:shadow-md bg-white"
                                 data-loan-type="3">
                                <div class="flex items-start gap-3">
                                    <div class="w-11 h-11 rounded-lg bg-cyan-100 flex items-center justify-center shrink-0">
                                        <i class="bx bx-water text-cyan-600 text-2xl"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h6 class="font-semibold text-slate-800 text-sm mb-1">Eco Water</h6>
                                        <p class="text-xs text-slate-500 leading-tight">Water project loan</p>
                                    </div>
                                    <i class="bx bx-check-circle text-cyan-600 text-xl hidden card-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Loan Amount --}}
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Loan Amount (TZS) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="loan_amount"
                               class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               min="1000" step="1000" placeholder="e.g. 500000" required>
                    </div>

                    {{-- Agent (loan_type 1, 2) --}}
                    <div class="mb-3" id="agent_field" style="display:none;">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Agent <span class="text-red-500">*</span>
                        </label>
                        <select name="agent_id" id="agent_id"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->user?->name ?? $agent->name ?? 'Agent #' . $agent->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Device (loan_type 2) --}}
                    <div class="mb-3" id="device_field" style="display:none;">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Device <span class="text-red-500">*</span>
                        </label>
                        <select name="device_id" id="device_id"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Device</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}"
                                        data-price="{{ $device->price }}"
                                        data-deposit="{{ $device->initial_deposit }}">
                                    {{ $device->name }} — TZS {{ number_format($device->price) }} (Deposit: {{ number_format($device->initial_deposit) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="initial_deposit_field" style="display:none;">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Initial Deposit (TZS)
                        </label>
                        <input type="number" name="initial_deposit" id="initial_deposit"
                               class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               min="0" step="1000" value="0">
                    </div>

                    {{-- Eco Water Campus (loan_type 3) --}}
                    <div class="mb-3" id="campus_field" style="display:none;">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                            Eco Water Campus <span class="text-red-500">*</span>
                        </label>
                        <select name="eco_water_campus_id" id="eco_water_campus_id"
                                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Campus</option>
                            @foreach($campuses as $campus)
                                <option value="{{ $campus->id }}">{{ $campus->campus_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bank Details --}}
                    <div class="border-t border-slate-200 pt-3 mt-4">
                        <h6 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">
                            Bank Details <span class="text-slate-400 normal-case font-normal">(optional)</span>
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Bank Name</label>
                                <select name="bank_name"
                                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Bank</option>
                                    <option value="CRDB">CRDB Bank</option>
                                    <option value="NMB">NMB Bank</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Account Number</label>
                                <input type="text" name="account_number"
                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g. 0150XXXXXXXX">
                            </div>
                        </div>
                    </div>

                    {{-- Calculator Preview --}}
                    <div class="alert alert-info mt-4 rounded-xl" id="calculator_preview" style="display:none;">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="bx bx-calculator text-blue-600"></i>
                            <span class="text-xs font-semibold text-blue-800 uppercase tracking-wider">Loan Breakdown</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-white/60 rounded-lg p-2">
                                <small class="text-slate-500 text-[11px] uppercase block mb-0.5">Loan Amount</small>
                                <div class="fw-bold text-sm text-slate-800" id="calc_amount">-</div>
                            </div>
                            <div class="bg-white/60 rounded-lg p-2">
                                <small class="text-slate-500 text-[11px] uppercase block mb-0.5">Interest</small>
                                <div class="fw-bold text-sm text-slate-800" id="calc_interest">-</div>
                            </div>
                            <div class="bg-white/60 rounded-lg p-2">
                                <small class="text-slate-500 text-[11px] uppercase block mb-0.5">Total Amount</small>
                                <div class="fw-bold text-sm text-slate-800" id="calc_total">-</div>
                            </div>
                            <div class="bg-white/60 rounded-lg p-2">
                                <small class="text-slate-500 text-[11px] uppercase block mb-0.5">Installment ×3</small>
                                <div class="fw-bold text-sm text-slate-800" id="calc_installment">-</div>
                            </div>
                        </div>
                    </div>

                    <div id="new_loan_alert"></div>
                </form>
            </div>
            <div class="px-6 py-4 bg-white border-t border-slate-100 flex justify-end gap-2">
                <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                    <i class="bx bx-x"></i> Cancel
                </button>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="submit_new_loan_btn">
                    <i class="bx bx-save"></i> Create Application
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* ═══ Select2 theming ═══ */
    #newLoanModal .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 4px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        background: #fff;
    }
    #newLoanModal .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        color: #334155;
        font-size: 13px;
    }
    #newLoanModal .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 6px;
    }
    #newLoanModal .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }
    #newLoanModal .select2-dropdown {
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    #newLoanModal .select2-search--dropdown .select2-search__field {
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 6px 10px;
        font-size: 13px;
    }
    #newLoanModal .select2-results__option--highlighted {
        background: #3b82f6 !important;
    }

    /* ═══ Loan type cards — selected state ═══ */
    .loan-type-card.is-selected {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        transform: translateY(-1px);
    }
    .loan-type-card.is-selected .card-check {
        display: block !important;
    }
    .loan-type-card:active {
        transform: scale(0.98);
    }

    /* ═══ Modal tweaks ═══ */
    #newLoanModal .modal-content {
        border-radius: 1rem;
    }
    #newLoanModal .modal-body {
        background: #f8fafc;
    }
    #newLoanModal .form-control:focus,
    #newLoanModal input:focus,
    #newLoanModal select:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
        outline: none;
    }

    /* ═══ Mobile ═══ */
    @media (max-width: 576px) {
        #newLoanModal .modal-dialog {
            margin: 0.5rem;
        }
        .loan-type-card {
            padding: 0.75rem !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // ═══════════════════════════════════════════════════════════════
    // 1. Select2 — customer search
    // ═══════════════════════════════════════════════════════════════
    $('#customer_select').select2({
        dropdownParent: $('#newLoanModal'),
        ajax: {
            url: "{{ route('customers.search') }}",
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        },
        minimumInputLength: 2,
        placeholder: 'Search customer by name, phone, ID...',
        allowClear: true
    });

    // ═══════════════════════════════════════════════════════════════
    // 2. Loan type cards — click handler
    // ═══════════════════════════════════════════════════════════════
    $(document).on('click', '.loan-type-card', function () {
        const type = $(this).data('loan-type');

        // Reset all cards
        $('.loan-type-card').removeClass('is-selected');
        $('.loan-type-card .card-check').addClass('hidden');

        // Highlight selected
        $(this).addClass('is-selected');
        $(this).find('.card-check').removeClass('hidden');

        // Set hidden input
        $('#loan_type').val(type);

        // Toggle fields
        $('#agent_field, #device_field, #initial_deposit_field, #campus_field').hide();

        if (type == '1') {
            $('#agent_field').show();
        } else if (type == '2') {
            $('#agent_field, #device_field, #initial_deposit_field').show();
        } else if (type == '3') {
            $('#campus_field').show();
        }

        calculatePreview();
    });

    // ═══════════════════════════════════════════════════════════════
    // 3. Device → auto-fill amount + deposit
    // ═══════════════════════════════════════════════════════════════
    $('#device_id').on('change', function () {
        const opt = $(this).find('option:selected');
        const price   = opt.data('price');
        const deposit = opt.data('deposit');

        if (price) $('#loan_amount').val(price);
        if (deposit !== undefined) $('#initial_deposit').val(deposit);

        calculatePreview();
    });

    // ═══════════════════════════════════════════════════════════════
    // 4. Calculator preview (AJAX)
    // ═══════════════════════════════════════════════════════════════
    let calcTimer = null;
    function calculatePreview() {
        clearTimeout(calcTimer);
        calcTimer = setTimeout(function () {
            const amount   = $('#loan_amount').val();
            const loanType = $('#loan_type').val();
            const deviceId = $('#device_id').val();

            if (!amount || !loanType || amount < 1000) {
                $('#calculator_preview').hide();
                return;
            }

            $.post("{{ route('loan.applications.calculate.web') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                amount: amount,
                loan_type: loanType,
                device_id: deviceId
            }, function (response) {
                if (response.success) {
                    const d = response.data;
                    $('#calc_amount').text('TZS ' + Number(d.amount).toLocaleString());
                    $('#calc_interest').text('TZS ' + Number(d.interest_amount).toLocaleString());
                    $('#calc_total').text('TZS ' + Number(d.total_amount).toLocaleString());
                    $('#calc_installment').text('TZS ' + Number(d.installment_amount).toLocaleString());
                    $('#calculator_preview').slideDown(200);
                }
            }).fail(function () {
                $('#calculator_preview').hide();
            });
        }, 500);
    }

    $('#loan_amount').on('input', calculatePreview);

    // ═══════════════════════════════════════════════════════════════
    // 5. Submit new loan
    // ═══════════════════════════════════════════════════════════════
    $('#submit_new_loan_btn').on('click', function () {
        const btn = $(this);
        const alertDiv = $('#new_loan_alert');
        const idle = '<i class="bx bx-save"></i> Create Application';

        // Validation
        if (!$('#customer_select').val()) {
            alertDiv.html('<div class="alert alert-danger"><i class="bx bx-error-circle me-2"></i>Please select a customer.</div>');
            return;
        }
        if (!$('#loan_type').val()) {
            alertDiv.html('<div class="alert alert-danger"><i class="bx bx-error-circle me-2"></i>Please select a loan type.</div>');
            return;
        }
        if (!$('#loan_amount').val() || $('#loan_amount').val() < 1000) {
            alertDiv.html('<div class="alert alert-danger"><i class="bx bx-error-circle me-2"></i>Please enter a valid loan amount (min TZS 1,000).</div>');
            return;
        }

        btn.html('<i class="bx bx-loader bx-spin"></i> Submitting...').prop('disabled', true);
        alertDiv.html('');

        $.ajax({
            type: 'POST',
            url: "{{ route('loan.applications.store.web') }}",
            data: new FormData($('#new_loan_form')[0]),
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (response) {
                alertDiv.html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>${response.message || 'Loan application created successfully'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
                setTimeout(() => location.reload(), 1200);
            },
            error: function (xhr) {
                let msgs = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(arr => {
                        arr.forEach(m => { msgs += `<div>${m}</div>`; });
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msgs = xhr.responseJSON.message;
                } else {
                    msgs = 'An error occurred. Check Network tab.';
                }
                alertDiv.html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>${msgs}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
            },
            complete: function () {
                btn.html(idle).prop('disabled', false);
            }
        });
    });

    // ═══════════════════════════════════════════════════════════════
    // 6. Reset modal on close
    // ═══════════════════════════════════════════════════════════════
    $('#newLoanModal').on('hidden.bs.modal', function () {
        $('#new_loan_form')[0].reset();
        $('#customer_select').val(null).trigger('change');

        // Reset cards
        $('.loan-type-card').removeClass('is-selected');
        $('.loan-type-card .card-check').addClass('hidden');
        $('#loan_type').val('');

        // Reset fields
        $('#agent_field, #device_field, #initial_deposit_field, #campus_field').hide();
        $('#calculator_preview').hide();
        $('#new_loan_alert').html('');
    });
});
</script>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- Confirm Mandate modal handler (independent) --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<script>
$(document).ready(function () {

    // Filter toggle
    $('#filter-btn').on('click', function (e) {
        e.preventDefault();
        $('#submit-form').slideToggle(150);
    });

    // Populate Confirm Mandate modal
    $(document).on('click', '.confirm-mandate-btn', function () {
        $('#mandate_loan_uuid').val(this.dataset.loanUuid);
        $('#mandate_customer_name').val(this.dataset.customerName);
        $('#mandate_loan_amount').val(this.dataset.loanAmount);
        $('#mandate_installment_amount').val(this.dataset.installmentAmount);
        $('#mandate_created_at').val(this.dataset.createdAt);
        $('#confirm_mandate_alert').html('');
    });

    // Submit Confirm Mandate
    $('#confirm_mandate_form').on('submit', function (e) {
        e.preventDefault();

        const btn = $('#confirm_mandate_btn');
        const alertDiv = $('#confirm_mandate_alert');
        const idle = '<span class="bx bx-check"></span> Confirm Mandate';

        btn.html('<i class="bx bx-loader bx-spin"></i> Loading...').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: "{{ route('confirm.mandate.loan.application') }}",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (response) {
                alertDiv.html('<div class="alert alert-success">' + response.message + '</div>');
                setTimeout(function () { location.reload(); }, 600);
            },
            error: function (xhr) {
                let msgs = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(arr => {
                        arr.forEach(m => { msgs += `<div>${m}</div>`; });
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    msgs = xhr.responseJSON.message;
                } else {
                    msgs = 'An error occurred.';
                }
                alertDiv.html('<div class="alert alert-danger">' + msgs + '</div>');
            },
            complete: function () {
                btn.html(idle).prop('disabled', false);
            }
        });
    });
});
</script>
@endpush