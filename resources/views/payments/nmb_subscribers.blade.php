@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">NMB Subscribers</span>
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
                    {{ $subscribers->count() }} Subscribers
                </span>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-user text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">NMB Bank Subscribers</h6>
            </div>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Account</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($subscribers as $subcriber)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($subcriber->consent_request?->created_at)) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $subcriber->nmb_username }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $subcriber->consent_request?->from_account_number }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @switch($subcriber->consent_request?->status)
                                        @case('APPROVED') <span class="badge bg-success">Approved</span> @break
                                        @case('PENDING') <span class="badge bg-warning">Pending</span> @break
                                        @case('REJECTED') <span class="badge bg-danger">Rejected</span> @break
                                        @default <span class="badge bg-secondary">{{ $subcriber->consent_request?->status }}</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                            data-id="{{ $subcriber->consent_request?->uuid }}"
                                            title="Create Transaction">
                                        <i class="bx bx-edit text-sm"></i> Transact
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
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-dollar-circle text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Create Transaction</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="uuid" id="id">
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                            Transaction Amount
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-slate-100 text-slate-600 font-semibold border-slate-200">TZS</span>
                            <input type="number" name="amount" class="form-control rounded-r-lg border-slate-200" placeholder="Enter amount..." required>
                        </div>
                    </div>

                    <div class="alert-container" id="update_alert"></div>

                    <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Close
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
                            <i class="bx bx-save"></i> Submit Transaction
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

