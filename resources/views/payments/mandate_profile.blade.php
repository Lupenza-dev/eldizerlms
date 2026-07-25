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
                <h5 class="mb-0 fw-bold text-primary">Mandate Profile</h5>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('payment.mandates') }}" class="text-muted text-decoration-none">
                                Payment Mandates
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group shadow-sm">
                    <a href="{{ route('payment.mandates') }}" class="btn btn-secondary px-4 py-2">
                        <i class="bx bx-arrow-back me-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Tabs Navigation -->
                <div class="border-bottom border-2 border-gray-200 mb-4">
                    <ul class="nav nav-pills" id="mandateTabs" role="tablist" style="background: transparent;">
                        <li class="nav-item me-2" role="presentation">
                            <button class="nav-link active px-4 py-3 rounded-top border-0 fw-semibold position-relative transition-all duration-300" 
                                    id="details-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#details" 
                                    type="button" 
                                    role="tab"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);">
                                <i class="bx bx-info-circle me-2"></i>
                                <span>Mandate Details</span>
                                <div class="position-absolute top-0 start-0 w-100 h-100 rounded-top" style="background: rgba(255,255,255,0.1);"></div>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-4 py-3 rounded-top border-0 fw-semibold position-relative transition-all duration-300" 
                                    id="collections-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#collections" 
                                    type="button" 
                                    role="tab"
                                    style="background: #f8f9fa; color: #6c757d; border-bottom: 3px solid transparent;">
                                <i class="bx bx-money me-2"></i>
                                <span>Payment Collections</span>
                                <span class="badge bg-light text-dark ms-2">{{ $collections->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <style>
                .nav-pills .nav-link {
                    transition: all 0.3s ease;
                    border-radius: 0.5rem 0.5rem 0 0 !important;
                }
                
                .nav-pills .nav-link:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
                }
                
                .nav-pills .nav-link.active {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                    color: white !important;
                    border-bottom: 3px solid #5a67d8 !important;
                    box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
                }
                
                .nav-pills .nav-link:not(.active):hover {
                    background: #e9ecef !important;
                    color: #495057 !important;
                    border-bottom: 3px solid #dee2e6 !important;
                }
                
                .badge {
                    font-size: 0.7em;
                    padding: 0.25em 0.5em;
                }
                
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .tab-pane {
                    animation: slideIn 0.3s ease-out;
                }
                </style>

                <!-- Tab Content -->
                <div class="tab-content" id="mandateTabsContent">
                    <!-- Mandate Details Tab -->
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <div class="d-flex justify-content-start mb-4">
                            <button type="button" class="inline-flex items-center gap-2 bg-red-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#updateMandateDescriptionModal">
                                <i class="bx bx-edit"></i> Cancel Mndate
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-primary mb-4">
                                            <i class="bx bx-file me-2"></i>Basic Information
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold text-muted">Customer</label>
                                                <p class="form-control-plaintext">{{ $payment->customer_mandate?->customer?->customer_name ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Reference</label>
                                                <p class="form-control-plaintext">{{ $payment->reference ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Channel</label>
                                                <p class="form-control-plaintext">{{ $payment->channel ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Periodicity</label>
                                                <p class="form-control-plaintext">{{ $payment->periodicity ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Debit Type</label>
                                                <p class="form-control-plaintext">{{ $payment->debit_type ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Number of Installments</label>
                                                <p class="form-control-plaintext">{{ $payment->number_of_installment ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">LifeCycle Status</label>
                                                <p class="form-control-plaintext">
                                                    @if($payment->lifecycle_status)
                                                        <span class="badge bg-success">{{ $payment->lifecycle_status }}</span>
                                                    @else
                                                        N/A
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold text-muted">Remarks</label>
                                                <p class="form-control-plaintext">
                                                    @if($payment->remarks)
                                                        <span class="badge bg-success">{{ $payment->remarks }}</span>
                                                    @else
                                                        N/A
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-primary mb-4">
                                            <i class="bx bx-dollar me-2"></i>Financial Information
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Installment Amount</label>
                                                <p class="form-control-plaintext fw-bold text-success">{{ number_format($payment->installment_amount, 2) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Min Installment</label>
                                                <p class="form-control-plaintext">{{ number_format($payment->min_installment_amount, 2) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Max Installment</label>
                                                <p class="form-control-plaintext">{{ number_format($payment->max_installment_amount, 2) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Total Amount</label>
                                                <p class="form-control-plaintext fw-bold text-primary">{{ number_format($payment->total_amount, 2) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Paid Amount</label>
                                                <p class="form-control-plaintext fw-bold text-success">{{ number_format($payment->paid_amount, 2) }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Outstanding Amount</label>
                                                <p class="form-control-plaintext fw-bold text-danger">{{ number_format($payment->outstanding_amount, 2) }}</p>
                                            </div>
                                            <div class="col-12">
                                                <div class="progress mt-3" style="height: 25px;">
                                                    <?php
                                                    $percentage = $payment->total_amount > 0 ? ($payment->paid_amount / $payment->total_amount) * 100 : 0;
                                                    ?>
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                                                        {{ round($percentage, 1) }}% Paid
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-primary mb-4">
                                            <i class="bx bx-calendar me-2"></i>Contract Period
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Start Date</label>
                                                <p class="form-control-plaintext">
                                                    <i class="bx bx-calendar me-2"></i>
                                                    {{ $payment->start_date ? date('d M Y', strtotime($payment->start_date)) : 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">End Date</label>
                                                <p class="form-control-plaintext">
                                                    <i class="bx bx-calendar me-2"></i>
                                                    {{ $payment->end_date ? date('d M Y', strtotime($payment->end_date)) : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Collections Tab -->
                    <div class="tab-pane fade" id="collections" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0 text-uppercase fw-bold text-dark">Payment Collections</h6>
                            <div class="d-flex gap-2">
                                <div class="badge bg-info" id="collection-count">
                                    {{ $collections->count() }} Collections
                                </div>
                                <a href="{{ route('sync.mandate.payment.collection', $payment->reference) }}" class="btn btn-primary btn-sm">
                                    <i class="bx bx-refresh me-1"></i>Sync Collections
                                </a>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="width:100%">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installment Order</th> --}}
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installment Amount</th>
                                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Installment</th> --}}
                                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Installment</th> --}}
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Balance</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outstanding</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Paid</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collections as $collection)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $collection->reference }}
                                                </span>
                                            </td>
                                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $collection->installment_order ?? 'N/A' }}</td> --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($collection->installment_amount, 2) }}</td>
                                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($collection->min_installment_amount, 2) }}</td> --}}
                                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($collection->max_installment_amount, 2) }}</td> --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">{{ number_format($collection->current_balance, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-medium">{{ number_format($collection->outstanding_amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $collection->payment_date ? date('d M Y', strtotime($collection->payment_date)) : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ number_format($collection->last_paid_amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($collection->status)
                                                    @switch($collection->status)
                                                        @case('PAID')
                                                            <span class="badge bg-success">Paid</span>
                                                            @break
                                                        @case('PENDING')
                                                            <span class="badge bg-warning">Pending</span>
                                                            @break
                                                        @case('OVERDUE')
                                                            <span class="badge bg-danger">Overdue</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ $collection->status }}</span>
                                                    @endswitch
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $collection->remarks ?? 'N/A' }}">
                                                    {{ $collection->remarks ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="bx bx-money bx-lg text-gray-400 mb-3" style="font-size: 3rem;"></i>
                                                    <p class="text-lg font-medium">No payment collections found</p>
                                                    <p class="text-sm mt-1">Click "Sync Collections" to load payment data</p>
                                                </div>
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
    </div>
</div>

<!-- Update Mandate Description Modal -->
<div class="modal fade" id="updateMandateDescriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Cancel Mandate</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_mandate_description_form">
                    <input type="hidden" name="reference" value="{{ $payment->reference }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Description / Remarks</label>
                            <textarea name="description" id="mandate_description" rows="5" class="form-control rounded-lg text-sm" placeholder="Enter description here..." required></textarea>
                        </div>
                        <div id="mandate_description_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_mandate_description_btn">
                            <i class="bx bx-save"></i> Cancel Mandate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const descriptionForm = document.getElementById('update_mandate_description_form');
    if (!descriptionForm) return;

    descriptionForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('update_mandate_description_btn');
        const alertDiv = document.getElementById('mandate_description_alert');

        // Loading state
        submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Saving...';
        submitBtn.disabled = true;
        alertDiv.innerHTML = '';

        const formData = new FormData(this);

        fetch("{{ route('cancel.mandate') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                setTimeout(() => location.reload(), 1500);
            } else {
                alertDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>${data.message || 'An error occurred'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>An error occurred. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        })
        .finally(() => {
            submitBtn.innerHTML = '<i class="bx bx-save me-1"></i> Cancel Mandate';
            submitBtn.disabled = false;
        });
    });
});
</script>

@endsection
