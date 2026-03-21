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
                <h5 class="mb-0 fw-bold text-primary">Device Management</h5>
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
                        {{ $devices->count() }} Devices
                    </div>
                    <button type="button" class="btn btn-primary px-3 py-2" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                        <i class="bx bx-plus text-white me-2"></i>
                        <span class="text-white">Add Device</span>
                    </button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
      
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">Device Inventory</h6>
                </div>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Initial Deposit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($devices as $device)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0">
                                        <img class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 shadow-sm" 
                                             src="{{ asset('storage/attachments').'/'.$device->image}}" 
                                             alt="{{ $device->name }}"
                                             onerror="this.src='https://picsum.photos/seed/device{{$device->id}}/64/64.jpg'">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($device->created_at)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $device->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($device->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $device->plan }} months</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">{{ number_format($device->initial_deposit, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $device->device_category?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="btn btn-danger btn-sm shadow-sm" id="{{ $device->uuid }}" onclick="delete_device(id)" title="Delete Device">
                                        <i class="bx bx-trash text-white"></i>
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

<!-- Device Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-mobile-alt me-2"></i>Device Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="registration_form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-tag me-2"></i>Device Name
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Enter device name..." required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">
                                <i class="bx bx-dollar me-2"></i>Device Price
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">TZS</span>
                                <input type="number" name="price" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="initial_deposit" class="form-label fw-semibold">
                                <i class="bx bx-wallet me-2"></i>Initial Deposit
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">TZS</span>
                                <input type="number" name="initial_deposit" class="form-control" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="plan" class="form-label fw-semibold">
                                <i class="bx bx-calendar me-2"></i>Payment Plan (months)
                            </label>
                            <input type="number" name="plan" class="form-control" placeholder="Enter payment plan..." required>
                        </div>
                        <div class="col-12">
                            <label for="device_category" class="form-label fw-semibold">
                                <i class="bx bx-category me-2"></i>Device Category
                            </label>
                            <select name="device_category" class="form-control" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="image" class="form-label fw-semibold">
                                <i class="bx bx-image me-2"></i>Device Image
                            </label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                        </div>
                        <div class="col-12" id="alert"></div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="reg_btn">
                                <i class="bx bx-save me-1"></i> Register Device
                            </button>
                        </div>
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
        const count = {{ $devices->count() }};
        recordCount.textContent = count + ' Device' + (count !== 1 ? 's' : '');
    }
    
    // Form submission
    document.getElementById('registration_form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const regBtn = document.getElementById('reg_btn');
        const alertDiv = document.getElementById('alert');
        
        // Show loading state
        regBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Registering...';
        regBtn.disabled = true;
        
        // Clear previous alerts
        alertDiv.innerHTML = '';
        
        const formData = new FormData(this);
        
        fetch("{{ route('devices.store') }}", {
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
            // Reset button state
            regBtn.innerHTML = '<i class="bx bx-save me-1"></i> Register Device';
            regBtn.disabled = false;
        });
    });
});

// Delete device function (keeping original SweetAlert)
function delete_device(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Delete Device",
        text: "Are you sure you want to Delete this Device?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('device.delete') }}", 
            method: "POST",
            data: {uuid:id,'_token':csrf_tokken,action:'deactivate'},
            success: function(response) { 
                $.notify(response.message, "success");
                setTimeout(function(){
                    location.reload();
                },500);
            },
            error: function(response){
                $.notify(response.responseJson.errors,'error');  
            }
        });
    });
}
</script>
@endsection