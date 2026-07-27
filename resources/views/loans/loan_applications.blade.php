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
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="col-md-12" id="confirm_mandate_alert" style="margin-top: 10px">

                    </div>
                    <div class="col-md-12 mt-4" style="text-align:right">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-x"></span> Close</button>
                        <button type="submit" class="btn btn-success" id="confirm_mandate_btn"> <span class="bx bx-check"></span> Confirm Mandate</button>
                    </div>
                </form>
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

    document.querySelectorAll('.confirm-mandate-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('mandate_loan_uuid').value = this.dataset.loanUuid;
            document.getElementById('mandate_customer_name').value = this.dataset.customerName;
            document.getElementById('mandate_loan_amount').value = this.dataset.loanAmount;
            document.getElementById('mandate_installment_amount').value = this.dataset.installmentAmount;
            document.getElementById('mandate_created_at').value = this.dataset.createdAt;
            document.getElementById('confirm_mandate_alert').innerHTML = '';
        });
    });

    $(document).ready(function(){
        $('#confirm_mandate_form').on('submit',function(e){
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type:'POST',
                url:"{{ route('confirm.mandate.loan.application') }}",
                data : new FormData(this),
                contentType: false,
                cache: false,
                processData : false,
                success:function(response){
                    console.log(response);
                    $('#confirm_mandate_alert').html('<div class="alert alert-success">'+response.message+'</div>');
                    setTimeout(function(){
                        location.reload();
                    },500);
                },
                error:function(response){
                    console.log(response.responseText);
                    if (jQuery.type(response.responseJSON.errors) == "object") {
                        $('#confirm_mandate_alert').html('');
                        $.each(response.responseJSON.errors,function(key,value){
                            $('#confirm_mandate_alert').append('<div class="alert alert-danger">'+value+'</div>');
                        });
                    } else {
                        $('#confirm_mandate_alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
                    }
                },
                beforeSend : function(){
                    $('#confirm_mandate_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Loading .........');
                    $('#confirm_mandate_btn').attr('disabled', true);
                },
                complete : function(){
                    $('#confirm_mandate_btn').html('<i class="bx bx-check"></i> Confirm Mandate');
                    $('#confirm_mandate_btn').attr('disabled', false);
                }
            });
        });
    });
});
</script>
@endsection

