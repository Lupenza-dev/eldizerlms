@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Agent Management</span>
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
                    {{ $agents->count() }} Agents
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                    <i class="bx bx-user-plus"></i> Add Agent
                </button>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-group text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Agent Directory</h6>
            </div>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Image</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">College</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($agents as $agent)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-slate-200 shadow-sm"
                                         src="{{ asset('storage/attachments').'/'.$agent->image}}"
                                         alt="{{ $agent->user?->name }}"
                                         onerror="this.src='https://picsum.photos/seed/agent{{$agent->id}}/48/48.jpg'">
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($agent->created_at)) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-semibold text-slate-800">{{ $agent->user?->name }}</span>
                                        <span class="text-xs text-slate-400">{{ $agent->user?->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $agent->user?->email }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-xs font-mono text-slate-600">{{ $agent->student_reg_id }}</span>
                                        <span class="text-xs text-slate-400">{{ $agent->college?->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{!! $agent->user?->status_formatted !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                                data-id="{{ $agent->uuid}}"
                                                data-name="{{ $agent->user?->name }}"
                                                data-email="{{ $agent->user?->email}}"
                                                data-phone_number="{{ $agent->user?->phone_number }}"
                                                data-student_reg="{{ $agent->student_reg_id }}"
                                                data-college_id="{{ $agent->college_id }}"
                                                title="Edit Agent">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        @if ($agent->user?->active == 2)
                                            <button class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors" id="{{ $agent->user?->uuid }}" onclick="enable_user(id)" title="Activate">
                                                <i class="bx bx-check text-sm"></i>
                                            </button>
                                        @else
                                            <button class="inline-flex items-center justify-center w-8 h-8 bg-amber-400 hover:bg-amber-500 text-white rounded-lg transition-colors" id="{{ $agent->user?->uuid }}" onclick="deactivate_user(id)" title="Deactivate">
                                                <i class="bx bx-x text-sm"></i>
                                            </button>
                                        @endif
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" id="{{ $agent->user?->uuid }}" onclick="delete_user(id)" title="Delete">
                                            <i class="bx bx-trash text-sm"></i>
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

<!-- Agent Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-user-plus text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Agent Registration</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="registration_form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-lg text-sm" placeholder="Enter full name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-lg text-sm" placeholder="Enter email address..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control rounded-lg text-sm" placeholder="Enter phone number..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Registration</label>
                            <input type="text" name="student_reg_id" class="form-control rounded-lg text-sm" placeholder="Enter student reg ID..." required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                            <select name="college_id" class="form-control rounded-lg text-sm" required>
                                <option value="">Select a college</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Profile Image</label>
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
                            <i class="bx bx-save"></i> Register Agent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Agent Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Update Agent Details</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control rounded-lg text-sm" placeholder="Enter full name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control rounded-lg text-sm bg-slate-50" placeholder="Enter email address..." readonly>
                            <p class="text-xs text-slate-400 mt-1">Email cannot be changed</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" class="form-control rounded-lg text-sm" placeholder="Enter phone number..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Registration</label>
                            <input type="text" id="student_reg" name="student_reg_id" class="form-control rounded-lg text-sm" placeholder="Enter student reg ID..." required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                            <select name="college_id" id="college_id" class="form-control rounded-lg text-sm" required>
                                <option value="">Select a college</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2" id="update_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
                            <i class="bx bx-save"></i> Update Agent
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
        const count = {{ $agents->count() }};
        recordCount.textContent = count + ' Agent' + (count !== 1 ? 's' : '');
    }
    
    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const email = this.dataset.email;
            const phone_number = this.dataset.phone_number;
            const student_reg = this.dataset.student_reg;
            const college_id = this.dataset.college_id;

            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('phone_number').value = phone_number;
            document.getElementById('student_reg').value = student_reg;
            document.getElementById('college_id').value = college_id;
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
        
        fetch("{{ route('agents.store') }}", {
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
            regBtn.innerHTML = '<i class="bx bx-save me-1"></i> Register Agent';
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
        
        fetch("{{ route('update.agent') }}", {
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
            updateBtn.innerHTML = '<i class="bx bx-save me-1"></i> Update Agent';
            updateBtn.disabled = false;
        });
    });
});

// User status functions (keeping original SweetAlert)
function enable_user(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Activate Agent",
        text: "Are you sure you want to Activate this Agent?",
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
        title: "Deactivate Agent",
        text: "Are you sure you want to Deactivate this Agent?",
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
        title: "Delete Agent",
        text: "Are you sure you want to Delete this Agent?",
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