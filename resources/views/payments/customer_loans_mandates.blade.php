@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Customer Mandates</span>
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
                        <i class="bx bx-user-check text-white text-xl"></i>
                    </div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">All Customer Mandates</h6>
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
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Application Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Loan Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Mandate Ref.</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Bank</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Account</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $payment)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($payment->loanApplication->created_at)) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $payment->customer?->customer_name ?? "N/A" }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($payment->loanApplication?->loan_amount ?? 0, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        {{ $payment->mandate_reference }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $payment->customer_bank_name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $payment->customer_account_number }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $payment->status === 'ACTIVE' ? 'bg-emerald-100 text-emerald-700' : ($payment->status === 'PENDING' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="btn-group">
                                        <button type="button" class="inline-flex items-center gap-1 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium px-3 py-1.5 rounded-l-lg transition-colors">Actions</button>
                                        <button type="button" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1.5 rounded-r-lg border-l border-slate-600 dropdown-toggle dropdown-toggle-split transition-colors" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu shadow-lg border-0 rounded-xl overflow-hidden">
                                            <li>
                                                <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="{{ route('view.payment.mandate',$payment->mandate_reference)}}">
                                                    <i class="bx bx-show text-blue-500"></i> View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item flex items-center gap-2 py-2 text-sm resend-otp-btn" href="javascript:;" data-reference="{{ $payment->mandate_reference }}">
                                                    <i class="bx bx-refresh text-emerald-500"></i> Resend OTP
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item flex items-center gap-2 py-2 text-sm verify-otp-btn" href="javascript:;" data-bs-toggle="modal" data-bs-target="#verifyOtpModal" data-reference="{{ $payment->mandate_reference }}">
                                                    <i class="bx bx-check-shield text-cyan-500"></i> Verify OTP
                                                </a>
                                            </li>
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

{{-- Verify OTP Modal --}}
<div class="modal fade" id="verifyOtpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-check-shield text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Verify OTP</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="verify_otp_form">
                    <input type="hidden" name="mandate_reference" id="otp_mandate_reference">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Mandate Reference</label>
                            <input type="text" id="otp_mandate_reference_display" class="form-control rounded-lg text-sm bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">OTP</label>
                            <input type="text" name="otp" class="form-control rounded-lg text-sm" placeholder="Enter OTP" required>
                        </div>
                        <div id="verify_otp_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Close
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="verify_otp_btn">
                            <i class="bx bx-check-shield"></i> Verify
                        </button>
                    </div>
                </form>
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
@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','.resend-otp-btn',function(){
        var btn = $(this);
        $.ajax({
            type:'POST',
            url:"{{ route('mandate.resend.otp') }}",
            data:{ mandate_reference: btn.data('reference') },
            success:function(response){
                alert(response.message);
            },
            error:function(response){
                alert(response.responseJSON?.message ?? 'Failed to resend OTP');
            },
            beforeSend:function(){
                btn.addClass('disabled').html('<i class="bx bx-loader bx-spin text-emerald-500"></i> Sending...');
            },
            complete:function(){
                btn.removeClass('disabled').html('<i class="bx bx-refresh text-emerald-500"></i> Resend OTP');
            }
        });
    });

    $(document).on('click','.verify-otp-btn',function(){
        $('#otp_mandate_reference').val($(this).data('reference'));
        $('#otp_mandate_reference_display').val($(this).data('reference'));
        $('#verify_otp_alert').html('');
        $('#verify_otp_form input[name=otp]').val('');
    });

    $('#verify_otp_form').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            type:'POST',
            url:"{{ route('mandate.verify.otp') }}",
            data : new FormData(this),
            contentType: false,
            cache: false,
            processData : false,
            success:function(response){
                $('#verify_otp_alert').html('<div class="alert alert-success">'+response.message+'</div>');
                setTimeout(function(){
                    location.reload();
                },1000);
            },
            error:function(response){
                if (jQuery.type(response.responseJSON?.errors) == "object") {
                    $('#verify_otp_alert').html('');
                    $.each(response.responseJSON.errors,function(key,value){
                        $('#verify_otp_alert').append('<div class="alert alert-danger">'+value+'</div>');
                    });
                } else {
                    $('#verify_otp_alert').html('<div class="alert alert-danger">'+(response.responseJSON?.message ?? 'Failed to verify OTP')+'</div>');
                }
            },
            beforeSend : function(){
                $('#verify_otp_btn').html('<i class="bx bx-loader bx-spin"></i> Verifying...');
                $('#verify_otp_btn').attr('disabled', true);
            },
            complete : function(){
                $('#verify_otp_btn').html('<i class="bx bx-check-shield"></i> Verify');
                $('#verify_otp_btn').attr('disabled', false);
            }
        });
    });
</script>
@endpush
