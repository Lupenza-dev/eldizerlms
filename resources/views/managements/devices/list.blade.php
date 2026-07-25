@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Device Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $devices->count() }} Devices
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                    <i class="bx bx-plus"></i> Add Device
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-devices text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Device Inventory</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Image</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Initial Deposit</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($devices as $device)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <img class="h-12 w-12 rounded-xl object-cover border-2 border-slate-200 shadow-sm"
                                         src="{{ asset('storage/attachments').'/'.$device->image}}"
                                         alt="{{ $device->name }}"
                                         onerror="this.src='https://picsum.photos/seed/device{{$device->id}}/64/64.jpg'">
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($device->created_at)) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $device->name }}</td>
                                <td class="px-4 py-3 text-sm font-bold text-slate-700 whitespace-nowrap">{{ number_format($device->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ $device->plan }} months</td>
                                <td class="px-4 py-3 text-sm font-medium text-blue-600 whitespace-nowrap">{{ number_format($device->initial_deposit, 2) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        {{ $device->device_category?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" id="{{ $device->uuid }}" onclick="delete_device(id)" title="Delete Device">
                                        <i class="bx bx-trash text-sm"></i>
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
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-mobile-alt text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Device Registration</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="registration_form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Name</label>
                            <input type="text" name="name" class="form-control rounded-lg text-sm" placeholder="Enter device name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Price</label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">TZS</span>
                                <input type="number" name="price" class="form-control rounded-r-lg text-sm" placeholder="0.00" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Initial Deposit</label>
                            <div class="input-group">
                                <span class="input-group-text text-sm">TZS</span>
                                <input type="number" name="initial_deposit" class="form-control rounded-r-lg text-sm" placeholder="0.00" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Payment Plan (months)</label>
                            <input type="number" name="plan" class="form-control rounded-lg text-sm" placeholder="Enter payment plan..." required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Category</label>
                            <select name="device_category" class="form-control rounded-lg text-sm" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Device Image</label>
                            <input type="file" name="image" class="form-control rounded-lg text-sm" accept="image/*" required>
                            <p class="text-xs text-slate-400 mt-1">Supported formats: JPG, PNG, GIF. Max size: 2MB</p>
                        </div>
                        <div class="sm:col-span-2" id="alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="reg_btn">
                            <i class="bx bx-save"></i> Register Device
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