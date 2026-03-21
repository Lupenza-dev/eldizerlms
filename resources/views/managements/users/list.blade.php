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
                <h5 class="mb-0 fw-bold text-primary">User Management</h5>
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
                        {{ $users->count() }} Users
                    </div>
                    <button type="button" class="btn btn-primary px-3 py-2" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                        <i class="bx bx-user-plus text-white me-2"></i>
                        <span class="text-white">Add User</span>
                    </button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">System Users</h6>
                </div>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($user->created_at)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $user->email }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $user->phone_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{!! $user->status_formatted !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="btn btn-primary btn-sm edit-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                                data-id="{{ $user->uuid}}" 
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email}}"
                                                data-phone_number="{{ $user->phone_number }}"
                                                title="Edit User">
                                            <i class="bx bx-edit text-white"></i>
                                        </button>

                                        @if ($user->active == 2)
                                            <button class="btn btn-success btn-sm shadow-sm" id="{{ $user->uuid }}" onclick="enable_user(id)" title="Activate">
                                                <i class="bx bx-check text-white"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-warning btn-sm shadow-sm" id="{{ $user->uuid }}" onclick="deactivate_user(id)" title="Deactivate">
                                                <i class="bx bx-x text-white"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger btn-sm shadow-sm" id="{{ $user->uuid }}" onclick="delete_user(id)" title="Delete">
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

<!-- User Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-user-plus me-2"></i>User Registration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="registration_form">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-user me-2"></i>Full Name
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Enter full name..." required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bx bx-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email address..." required>
                        </div>
                        <div class="col-12">
                            <label for="phone_number" class="form-label fw-semibold">
                                <i class="bx bx-phone me-2"></i>Phone Number
                            </label>
                            <input type="tel" name="phone_number" class="form-control" placeholder="Enter phone number..." required>
                        </div>
                        <div class="col-12" id="alert"></div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="reg_btn">
                                <i class="bx bx-save me-1"></i> Register User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-edit me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bx bx-user me-2"></i>Full Name
                            </label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter full name..." required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bx bx-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address..." readonly>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        <div class="col-12">
                            <label for="phone_number" class="form-label fw-semibold">
                                <i class="bx bx-phone me-2"></i>Phone Number
                            </label>
                            <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="Enter phone number..." required>
                        </div>
                        <div class="col-12" id="update_alert"></div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-info" id="update_btn">
                                <i class="bx bx-save me-1"></i> Update User
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
        const count = {{ $users->count() }};
        recordCount.textContent = count + ' User' + (count !== 1 ? 's' : '');
    }
    
    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const email = this.dataset.email;
            const phone_number = this.dataset.phone_number;

            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('phone_number').value = phone_number;
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
        
        fetch("{{ route('users.store') }}", {
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
            regBtn.innerHTML = '<i class="bx bx-save me-1"></i> Register User';
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
        
        fetch("{{ route('update.user') }}", {
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
            updateBtn.innerHTML = '<i class="bx bx-save me-1"></i> Update User';
            updateBtn.disabled = false;
        });
    });
});

// User status functions (keeping original SweetAlert)
function enable_user(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Activate User",
        text: "Are you sure you want to Activate this User?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        confirmButtonText: "Yes, Activate",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('user.status') }}", 
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

function deactivate_user(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Deactivate User",
        text: "Are you sure you want to Deactivate this User?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ffc107",
        confirmButtonText: "Yes, Deactivate",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('user.status') }}", 
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

function delete_user(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Delete User",
        text: "Are you sure you want to Delete this User?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('user.delete') }}", 
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