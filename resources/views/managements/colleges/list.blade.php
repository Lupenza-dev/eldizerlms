@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">University Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-building"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $colleges->count() }} Universities
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                    <i class="bx bx-plus"></i> Add University
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-building text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">University Directory</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Logo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Representative</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($colleges as $college)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <img class="h-10 w-10 rounded-lg object-cover border-2 border-slate-200 shadow-sm"
                                         src="{{ asset('storage/attachments').'/'.$college->logo }}"
                                         alt="{{ $college->name }}"
                                         onerror="this.src='https://picsum.photos/seed/college{{$college->id}}/48/48.jpg'">
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($college->created_at)) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-semibold text-slate-800">{{ $college->name }}</span>
                                        <span class="text-xs text-slate-400">{{ $college->location }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-medium text-slate-700">{{ $college->representative?->name ?? 'N/A' }}</span>
                                        <span class="text-xs text-slate-400">{{ $college->representative?->position ?? 'N/A' }}</span>
                                        <span class="text-xs text-slate-400">{{ $college->representative?->phone_number ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{!! $college->status_formatted !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                                data-id="{{ $college->uuid}}"
                                                data-name="{{ $college->name}}"
                                                data-location="{{ $college->location}}"
                                                data-rep_name="{{ $college->representative?->name }}"
                                                data-rep_phone="{{ $college->representative?->phone_number }}"
                                                data-rep_position="{{ $college->representative?->position }}"
                                                title="Edit University">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        @if ($college->status == "Inactive")
                                            <button class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors" id="{{ $college->uuid }}" onclick="enable_college(id)" title="Activate">
                                                <i class="bx bx-check text-sm"></i>
                                            </button>
                                        @else
                                            <button class="inline-flex items-center justify-center w-8 h-8 bg-amber-400 hover:bg-amber-500 text-white rounded-lg transition-colors" id="{{ $college->uuid }}" onclick="disable_college(id)" title="Deactivate">
                                                <i class="bx bx-x text-sm"></i>
                                            </button>
                                        @endif
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" id="{{ $college->uuid }}" onclick="delete_college(id)" title="Delete">
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

<!-- University Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-building text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">University Registration</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="registration_form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">University Name</label>
                            <input type="text" name="name" class="form-control rounded-lg text-sm" placeholder="Enter university name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Location</label>
                            <textarea name="location" class="form-control rounded-lg text-sm" placeholder="Enter university location..." rows="3"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">University Logo</label>
                            <input type="file" name="logo" class="form-control rounded-lg text-sm" accept="image/*" required>
                            <p class="text-xs text-slate-400 mt-1">Supported formats: JPG, PNG, GIF. Max size: 2MB</p>
                        </div>
                        <div class="pt-2 pb-1">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex-1 h-px bg-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Representative Information</span>
                                <div class="flex-1 h-px bg-slate-200"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Representative Name</label>
                                    <input type="text" name="rep_name" class="form-control rounded-lg text-sm" placeholder="Name..." required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                    <input type="text" name="rep_phone_number" class="form-control rounded-lg text-sm" placeholder="Phone..." required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Position</label>
                                    <input type="text" name="position" class="form-control rounded-lg text-sm" placeholder="Position..." required>
                                </div>
                            </div>
                        </div>
                        <div id="alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="reg_btn">
                            <i class="bx bx-save"></i> Register University
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit University Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Update University Details</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">University Name</label>
                            <input type="text" id="name" name="name" class="form-control rounded-lg text-sm" placeholder="Enter university name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Location</label>
                            <textarea name="location" id="location" class="form-control rounded-lg text-sm" placeholder="Enter university location..." rows="3"></textarea>
                        </div>
                        <div class="pt-2 pb-1">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex-1 h-px bg-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Representative Information</span>
                                <div class="flex-1 h-px bg-slate-200"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Representative Name</label>
                                    <input type="text" id="rep_name" name="rep_name" class="form-control rounded-lg text-sm" placeholder="Name..." required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                    <input type="text" id="rep_phone" name="rep_phone_number" class="form-control rounded-lg text-sm" placeholder="Phone..." required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Position</label>
                                    <input type="text" id="rep_position" name="position" class="form-control rounded-lg text-sm" placeholder="Position..." required>
                                </div>
                            </div>
                        </div>
                        <div id="update_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
                            <i class="bx bx-save"></i> Update University
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