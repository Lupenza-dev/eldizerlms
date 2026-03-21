@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
</style>
<div class="page-wrapper bg-light">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">
                <h5 class="mb-0 fw-bold text-primary">NMB Subscribers</h5>
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
                    <div class="badge bg-success" id="record-count">
                        {{ $subscribers->count() }} Subscribers
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">NMB Bank Subscribers</h6>
                    {{-- <div class="d-flex gap-2">
                        <button class="btn btn-success px-3 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit">
                            <i class="bx bx-plus-circle text-white me-2"></i>
                            <span class="text-white">Create Transaction</span>
                        </button>
                    </div> --}}
                </div>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($subscribers as $subcriber)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($subcriber->consent_request?->created_at)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $subcriber->nmb_username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $subcriber->consent_request?->from_account_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($subcriber->consent_request?->status)
                                        @case('APPROVED')
                                            <span class="badge bg-success">Approved</span>
                                            @break
                                        @case('PENDING')
                                            <span class="badge bg-warning">Pending</span>
                                            @break
                                        @case('REJECTED')
                                            <span class="badge bg-danger">Rejected</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $subcriber->consent_request?->status }}</span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="btn btn-primary btn-sm edit-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                            data-id="{{ $subcriber->consent_request?->uuid }}" 
                                            title="Create Transaction">
                                        <i class="bx bx-edit"></i>
                                    </button>
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

<!-- Transaction Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-dollar-circle me-2"></i>Create Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="update_form">
                    <input type="hidden" name="uuid" id="id">
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold">
                            <i class="bx bx-money me-2"></i>Transaction Amount
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">TZS</span>
                            <input type="number" name="amount" class="form-control" placeholder="Enter amount..." required>
                        </div>
                    </div>
                    
                    <div class="alert-container" id="update_alert"></div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="update_btn">
                            <i class="bx bx-save me-1"></i> Submit Transaction
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
    if(recordCount) {
        const count = {{ $subscribers->count() }};
        recordCount.textContent = count + ' Subscriber' + (count !== 1 ? 's' : '');
    }
    
    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('id').value = id;
        });
    });
    
    // Form submission
    document.getElementById('update_form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const updateBtn = document.getElementById('update_btn');
        const updateAlert = document.getElementById('update_alert');
        
        // Show loading state
        updateBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Processing...';
        updateBtn.disabled = true;
        
        // Clear previous alerts
        updateAlert.innerHTML = '';
        
        fetch("{{ route('create.transaction') }}", {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAlert.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                setTimeout(() => location.reload(), 1500);
            } else {
                updateAlert.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>${data.message || 'An error occurred'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            updateAlert.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>An error occurred. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        })
        .finally(() => {
            // Reset button state
            updateBtn.innerHTML = '<i class="bx bx-save me-1"></i> Submit Transaction';
            updateBtn.disabled = false;
        });
    });
});
</script>
@endsection

