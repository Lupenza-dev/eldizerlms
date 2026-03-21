@extends('layouts.master')

@section('content')
<style>
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
                <h5 class="mb-0 fw-bold text-primary">University Management</h5>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;" class="text-muted text-decoration-none">
                                <i class="bx bx-building text-primary"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group shadow-sm">
                    <div class="badge bg-success" id="record-count">
                        {{ $colleges->count() }} Universities
                    </div>
                    <button type="button" class="btn btn-primary px-3 py-2" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                        <i class="bx bx-plus text-white me-2"></i>
                        <span class="text-white">Add University</span>
                    </button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">University Directory</h6>
                </div>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Representative</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($colleges as $college)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-lg object-cover border-2 border-gray-200 shadow-sm" 
                                             src="{{ asset('storage/attachments').'/'.$college->logo }}" 
                                             alt="{{ $college->name }}"
                                             onerror="this.src='https://picsum.photos/seed/college{{$college->id}}/48/48.jpg'">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($college->created_at)) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $college->name }}</span>
                                        <span class="text-gray-500 text-sm">{{ $college->location }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $college->representative?->name ?? 'N/A' }}</span>
                                        <span class="text-gray-500 text-xs">{{ $college->representative?->position ?? 'N/A' }}</span>
                                        <span class="text-gray-500 text-xs">{{ $college->representative?->phone_number ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{!! $college->status_formatted !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="btn btn-primary btn-sm edit-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                                data-id="{{ $college->uuid}}" 
                                                data-name="{{ $college->name}}"
                                                data-location="{{ $college->location}}"
                                                data-rep_name="{{ $college->representative?->name }}"
                                                data-rep_phone="{{ $college->representative?->phone_number }}"
                                                data-rep_position="{{ $college->representative?->position }}"
                                                title="Edit University">
                                            <i class="bx bx-edit text-white"></i>
                                        </button>

                                        @if ($college->status == "Inactive")
                                            <button class="btn btn-success btn-sm shadow-sm" id="{{ $college->uuid }}" onclick="enable_college(id)" title="Activate">
                                                <i class="bx bx-check text-white"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-warning btn-sm shadow-sm" id="{{ $college->uuid }}" onclick="disable_college(id)" title="Deactivate">
                                                <i class="bx bx-x text-white"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger btn-sm shadow-sm" id="{{ $college->uuid }}" onclick="delete_college(id)" title="Delete">
                                            <i class="bx bx-trash text-white"></i>
                                        </button>
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

<!-- University Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-building me-2"></i>University Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="registration_form">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-tag me-2"></i>University Name
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Enter university name..." required>
                        </div>
                        <div class="col-12">
                            <label for="location" class="form-label fw-semibold">
                                <i class="bx bx-map me-2"></i>Location
                            </label>
                            <textarea name="location" class="form-control" placeholder="Enter university location..." rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="logo" class="form-label fw-semibold">
                                <i class="bx bx-image me-2"></i>University Logo
                            </label>
                            <input type="file" name="logo" class="form-control" accept="image/*" required>
                            <small class="text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                        </div>
                        
                        <div class="col-12">
                            <hr class="my-3">
                            <h6 class="text-center fw-bold text-primary">
                                <i class="bx bx-user me-2"></i>Representative Information
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="rep_name" class="form-label fw-semibold">
                                <i class="bx bx-user me-2"></i>Representative Name
                            </label>
                            <input type="text" name="rep_name" class="form-control" placeholder="Enter representative name..." required>
                        </div>
                        <div class="col-md-4">
                            <label for="rep_phone_number" class="form-label fw-semibold">
                                <i class="bx bx-phone me-2"></i>Phone Number
                            </label>
                            <input type="text" name="rep_phone_number" class="form-control" placeholder="Enter phone number..." required>
                        </div>
                        <div class="col-md-4">
                            <label for="position" class="form-label fw-semibold">
                                <i class="bx bx-briefcase me-2"></i>Position
                            </label>
                            <input type="text" name="position" class="form-control" placeholder="Enter position..." required>
                        </div>
                        <div class="col-12" id="alert"></div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="reg_btn">
                                <i class="bx bx-save me-1"></i> Register University
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit University Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-edit me-2"></i>University Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-tag me-2"></i>University Name
                            </label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter university name..." required>
                        </div>
                        <div class="col-12">
                            <label for="location" class="form-label fw-semibold">
                                <i class="bx bx-map me-2"></i>Location
                            </label>
                            <textarea name="location" id="location" class="form-control" placeholder="Enter university location..." rows="3"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <hr class="my-3">
                            <h6 class="text-center fw-bold text-info">
                                <i class="bx bx-user me-2"></i>Representative Information
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="rep_name" class="form-label fw-semibold">
                                <i class="bx bx-user me-2"></i>Representative Name
                            </label>
                            <input type="text" id="rep_name" name="rep_name" class="form-control" placeholder="Enter representative name..." required>
                        </div>
                        <div class="col-md-4">
                            <label for="rep_phone" class="form-label fw-semibold">
                                <i class="bx bx-phone me-2"></i>Phone Number
                            </label>
                            <input type="text" id="rep_phone" name="rep_phone_number" class="form-control" placeholder="Enter phone number..." required>
                        </div>
                        <div class="col-md-4">
                            <label for="rep_position" class="form-label fw-semibold">
                                <i class="bx bx-briefcase me-2"></i>Position
                            </label>
                            <input type="text" id="rep_position" name="position" class="form-control" placeholder="Enter position..." required>
                        </div>
                        <div class="col-12" id="update_alert"></div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-info" id="update_btn">
                                <i class="bx bx-save me-1"></i> Update University
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
        const count = {{ $colleges->count() }};
        recordCount.textContent = count + ' Universit' + (count !== 1 ? 'ies' : 'y');
    }
    
    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const location = this.dataset.location;
            const rep_name = this.dataset.rep_name;
            const rep_phone = this.dataset.rep_phone;
            const rep_position = this.dataset.rep_position;

            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('location').value = location;
            document.getElementById('rep_name').value = rep_name;
            document.getElementById('rep_phone').value = rep_phone;
            document.getElementById('rep_position').value = rep_position;
        });
    });
    
    // Registration form submission
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
        
        fetch("{{ route('colleges.store') }}", {
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
            regBtn.innerHTML = '<i class="bx bx-save me-1"></i> Register University';
            regBtn.disabled = false;
        });
    });
    
    // Update form submission
    document.getElementById('update_form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const updateBtn = document.getElementById('update_btn');
        const updateAlert = document.getElementById('update_alert');
        
        // Show loading state
        updateBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Updating...';
        updateBtn.disabled = true;
        
        // Clear previous alerts
        updateAlert.innerHTML = '';
        
        const formData = new FormData(this);
        
        fetch("{{ route('update.college') }}", {
            method: 'POST',
            body: formData,
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
            updateBtn.innerHTML = '<i class="bx bx-save me-1"></i> Update University';
            updateBtn.disabled = false;
        });
    });
});

// College status functions (keeping original SweetAlert)
function enable_college(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Activate University",
        text: "Are you sure you want to Activate this University?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        confirmButtonText: "Yes, Activate",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('college.status') }}", 
            method: "POST",
            data: {uuid:id,'_token':csrf_tokken,action:'activate'},
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

function disable_college(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Deactivate University",
        text: "Are you sure you want to Deactivate this University?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ffc107",
        confirmButtonText: "Yes, Deactivate",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('college.status') }}", 
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

function delete_college(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Delete University",
        text: "Are you sure you want to Delete this University?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('college.delete') }}", 
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