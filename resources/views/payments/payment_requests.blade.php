@extends('layouts.master')
@section('content')
@php $canAct = auth()->user()->canAny(['approve payment requests', 'reject payment requests']); @endphp
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Payment Requests</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('payment.management') }}" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payment.all') }}" class="text-slate-400">All Payments</a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">Payment Requests</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <span class="inline-flex items-center bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                    {{ $payment_requests->count() }} {{ \Illuminate\Support\Str::plural('Record', $payment_requests->count()) }}
                </span>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-time-five text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Repayment Requests</h6>
            </div>

            <div class="px-6 pt-4" id="request_alert"></div>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Payment Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Contract</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reference</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Method</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Channel</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Added By</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Remark</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                @if ($canAct)
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($payment_requests as $item)
                            @php $customer = $item->loan_contract?->customer; @endphp
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($item->payment_date)) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-semibold text-slate-800">{{ trim(($customer->first_name ?? '').' '.($customer->last_name ?? '')) ?: 'N/A' }}</span>
                                        <span class="text-slate-400 text-xs">{{ $item->phone_number ?? $customer?->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap">
                                    @if ($item->loan_contract)
                                        <a href="{{ route('loan.contract.profile', $item->loan_contract->uuid) }}" class="text-blue-600 hover:underline">
                                            {{ $item->loan_contract->contract_code ?? 'View' }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ number_format($item->amount, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $item->payment_reference }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $item->payment_method }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $item->payment_channel }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $item->addedBy?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">
                                    <span class="inline-block max-w-[180px] truncate" title="{{ $item->remarks ?? 'N/A' }}">
                                        {{ $item->remarks ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($item->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                @if ($canAct)
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if ($item->status === 'pending')
                                        @can('approve payment requests')
                                            <button type="button" class="btn btn-success btn-sm approve-btn" data-uuid="{{ $item->uuid }}">
                                                <i class="bx bx-check"></i> Approve
                                            </button>
                                        @endcan
                                        @can('reject payment requests')
                                            <button type="button" class="btn btn-danger btn-sm reject-btn" data-uuid="{{ $item->uuid }}" data-reference="{{ $item->payment_reference }}">
                                                <i class="bx bx-x"></i> Reject
                                            </button>
                                        @endcan
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
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

{{-- Reject Modal --}}
<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Payment Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reject_request_form">
                    <input type="hidden" name="uuid" id="reject_uuid">
                    <p class="text-sm text-slate-500 mb-2">Reference: <strong id="reject_reference"></strong></p>
                    <label for="reject_remarks">Reason for rejection <span class="text-danger">*</span></label>
                    <textarea name="remarks" id="reject_remarks" class="form-control" rows="3" placeholder="Write the reason for rejecting..." required minlength="3"></textarea>
                    <div id="reject_request_alert" style="margin-top: 10px"></div>
                    <div style="text-align:right; margin-top: 10px">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><span class="bx bx-x"></span> Close</button>
                        <button type="submit" class="btn btn-danger" id="reject_request_btn"><span class="bx bx-block"></span> Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        function errorHtml(response) {
            var json = response.responseJSON || {};
            if (json.errors && typeof json.errors === 'object') {
                var html = '';
                $.each(json.errors, function (key, value) {
                    html += '<div class="alert alert-danger">' + value + '</div>';
                });
                return html;
            }
            return '<div class="alert alert-danger">' + (json.errors || json.message || 'Something went wrong') + '</div>';
        }

        // Approve (placeholder — no approval logic yet)
        $(document).on('click', '.approve-btn', function () {
            if (!confirm('Approve this payment request?')) {
                return;
            }
            var btn = $(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('payment.request.approve') }}",
                data: { uuid: btn.data('uuid') },
                beforeSend: function () { btn.attr('disabled', true); },
                complete: function () { btn.attr('disabled', false); },
                success: function (response) {
                    $('#request_alert').html('<div class="alert alert-info">' + response.message + '</div>');
                },
                error: function (response) {
                    $('#request_alert').html(errorHtml(response));
                }
            });
        });

        // Reject — reason is mandatory
        $(document).on('click', '.reject-btn', function () {
            $('#reject_uuid').val($(this).data('uuid'));
            $('#reject_reference').text($(this).data('reference'));
            $('#reject_remarks').val('');
            $('#reject_request_alert').html('');
            $('#rejectRequestModal').modal('show');
        });

        $('#reject_request_form').on('submit', function (e) {
            e.preventDefault();
            if ($.trim($('#reject_remarks').val()).length < 3) {
                $('#reject_request_alert').html('<div class="alert alert-danger">Please provide the reason for rejecting this payment request</div>');
                return;
            }
            $.ajax({
                type: 'POST',
                url: "{{ route('payment.request.reject') }}",
                data: $(this).serialize(),
                beforeSend: function () {
                    $('#reject_request_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Loading...').attr('disabled', true);
                },
                complete: function () {
                    $('#reject_request_btn').html('<span class="bx bx-block"></span> Reject').attr('disabled', false);
                },
                success: function (response) {
                    $('#reject_request_alert').html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function () { location.reload(); }, 800);
                },
                error: function (response) {
                    $('#reject_request_alert').html(errorHtml(response));
                }
            });
        });
    });
</script>
@endpush
