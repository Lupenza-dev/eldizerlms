@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Hospital Management</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-buildings"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto flex items-center gap-3">
                <span class="inline-flex items-center bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1.5 rounded-full" id="record-count">
                    {{ $hospitals->count() }} Hospitals
                </span>
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                    <i class="bx bx-plus"></i> Add Hospital
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-buildings text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Hospital Directory</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Short Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Region / District</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Contact Person</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($hospitals as $hospital)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($hospital->created_at)) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-semibold text-slate-800">{{ $hospital->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">{{ $hospital->short_name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm text-slate-700">{{ $hospital->region?->name ?? 'N/A' }}</span>
                                        <span class="text-xs text-slate-400">{{ $hospital->district?->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-medium text-slate-700">{{ $hospital->contactPerson?->name ?? 'N/A' }}</span>
                                        <span class="text-xs text-slate-400">{{ $hospital->contactPerson?->email ?? 'N/A' }}</span>
                                        <span class="text-xs text-slate-400">{{ $hospital->contactPerson?->phone_number ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($hospital->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                                data-id="{{ $hospital->uuid}}"
                                                data-name="{{ $hospital->name}}"
                                                data-short_name="{{ $hospital->short_name }}"
                                                data-region_id="{{ $hospital->region_id }}"
                                                data-district_id="{{ $hospital->district_id }}"
                                                data-contact_name="{{ $hospital->contactPerson?->name }}"
                                                data-contact_email="{{ $hospital->contactPerson?->email }}"
                                                data-contact_phone="{{ $hospital->contactPerson?->phone_number }}"
                                                title="Edit Hospital">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        @if ($hospital->status == "Inactive")
                                            <button class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition-colors" id="{{ $hospital->uuid }}" onclick="enable_hospital(id)" title="Activate">
                                                <i class="bx bx-check text-sm"></i>
                                            </button>
                                        @else
                                            <button class="inline-flex items-center justify-center w-8 h-8 bg-amber-400 hover:bg-amber-500 text-white rounded-lg transition-colors" id="{{ $hospital->uuid }}" onclick="disable_hospital(id)" title="Deactivate">
                                                <i class="bx bx-x text-sm"></i>
                                            </button>
                                        @endif
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" id="{{ $hospital->uuid }}" onclick="delete_hospital(id)" title="Delete">
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

<!-- Hospital Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-buildings text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Hospital Registration</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="registration_form">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hospital Name</label>
                                <input type="text" name="name" class="form-control rounded-lg text-sm" placeholder="Enter hospital name..." required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Short Name</label>
                                <input type="text" name="short_name" class="form-control rounded-lg text-sm" placeholder="Enter short name...">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Region</label>
                                <select name="region_id" id="region_id" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">District</label>
                                <select name="district_id" id="district_id" class="form-control rounded-lg text-sm" required disabled>
                                    <option value="">Select District</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-2 pb-1">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex-1 h-px bg-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Person</span>
                                <div class="flex-1 h-px bg-slate-200"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Name</label>
                                    <input type="text" name="contact_name" class="form-control rounded-lg text-sm" placeholder="Name..." required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                                    <input type="email" name="contact_email" class="form-control rounded-lg text-sm" placeholder="Email...">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                    <input type="text" name="contact_phone" class="form-control rounded-lg text-sm" placeholder="Phone..." required>
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
                            <i class="bx bx-save"></i> Register Hospital
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Hospital Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Update Hospital Details</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hospital Name</label>
                                <input type="text" id="name" name="name" class="form-control rounded-lg text-sm" placeholder="Enter hospital name..." required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Short Name</label>
                                <input type="text" id="short_name" name="short_name" class="form-control rounded-lg text-sm" placeholder="Enter short name...">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Region</label>
                                <select name="region_id" id="edit_region_id" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">District</label>
                                <select name="district_id" id="edit_district_id" class="form-control rounded-lg text-sm" required disabled>
                                    <option value="">Select District</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-2 pb-1">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex-1 h-px bg-slate-200"></div>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Person</span>
                                <div class="flex-1 h-px bg-slate-200"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Name</label>
                                    <input type="text" id="contact_name" name="contact_name" class="form-control rounded-lg text-sm" placeholder="Name..." required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                                    <input type="email" id="contact_email" name="contact_email" class="form-control rounded-lg text-sm" placeholder="Email...">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control rounded-lg text-sm" placeholder="Phone..." required>
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
                            <i class="bx bx-save"></i> Update Hospital
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
        const count = {{ $hospitals->count() }};
        recordCount.textContent = count + ' Hospital' + (count !== 1 ? 's' : '');
    }

    const regionSelect = document.getElementById('region_id');
    const districtSelect = document.getElementById('district_id');
    const editRegionSelect = document.getElementById('edit_region_id');
    const editDistrictSelect = document.getElementById('edit_district_id');

    function loadDistricts(regionId, districtSelect, selectedDistrictId = null) {
        if (!regionId) {
            districtSelect.innerHTML = '<option value="">Select District</option>';
            districtSelect.disabled = true;
            return;
        }

        districtSelect.disabled = true;
        fetch("{{ url('hospital/districts') }}/" + regionId)
            .then(response => response.json())
            .then(data => {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                if (data.success) {
                    data.data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        if (selectedDistrictId && district.id == selectedDistrictId) {
                            option.selected = true;
                        }
                        districtSelect.appendChild(option);
                    });
                }
                districtSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading districts:', error);
                districtSelect.innerHTML = '<option value="">Select District</option>';
                districtSelect.disabled = false;
            });
    }

    regionSelect.addEventListener('change', function() {
        loadDistricts(this.value, districtSelect);
    });

    editRegionSelect.addEventListener('change', function() {
        loadDistricts(this.value, editDistrictSelect);
    });

    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const short_name = this.dataset.short_name;
            const region_id = this.dataset.region_id;
            const district_id = this.dataset.district_id;
            const contact_name = this.dataset.contact_name;
            const contact_email = this.dataset.contact_email;
            const contact_phone = this.dataset.contact_phone;

            document.getElementById('id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('short_name').value = short_name;
            document.getElementById('edit_region_id').value = region_id;
            loadDistricts(region_id, editDistrictSelect, district_id);
            document.getElementById('contact_name').value = contact_name;
            document.getElementById('contact_email').value = contact_email;
            document.getElementById('contact_phone').value = contact_phone;
        });
    });

    // Registration form submission
    document.getElementById('registration_form').addEventListener('submit', function(e) {
        e.preventDefault();

        const regBtn = document.getElementById('reg_btn');
        const alertDiv = document.getElementById('alert');

        regBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Registering...';
        regBtn.disabled = true;
        alertDiv.innerHTML = '';

        const formData = new FormData(this);

        fetch("{{ route('hospitals.store') }}", {
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
            regBtn.innerHTML = '<i class="bx bx-save me-1"></i> Register Hospital';
            regBtn.disabled = false;
        });
    });

    // Update form submission
    document.getElementById('update_form').addEventListener('submit', function(e) {
        e.preventDefault();

        const updateBtn = document.getElementById('update_btn');
        const updateAlert = document.getElementById('update_alert');

        updateBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Updating...';
        updateBtn.disabled = true;
        updateAlert.innerHTML = '';

        const formData = new FormData(this);

        fetch("{{ route('update.hospital') }}", {
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
            updateBtn.innerHTML = '<i class="bx bx-save me-1"></i> Update Hospital';
            updateBtn.disabled = false;
        });
    });
});

function enable_hospital(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Activate Hospital",
        text: "Are you sure you want to Activate this Hospital?",
        type: "success",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        confirmButtonText: "Yes, Activate",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('hospital.status') }}",
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

function disable_hospital(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Deactivate Hospital",
        text: "Are you sure you want to Deactivate this Hospital?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ffc107",
        confirmButtonText: "Yes, Deactivate",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('hospital.status') }}",
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

function delete_hospital(id){
    var csrf_tokken = $('meta[name="csrf-token"]').attr('content');
    swal({
        title: "Delete Hospital",
        text: "Are you sure you want to Delete this Hospital?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel",
        closeOnConfirmation: false
    },
    function(){
        $.ajax({
            url: "{{ route('hospital.delete') }}",
            method: "POST",
            data: {uuid:id,'_token':csrf_tokken,action:'delete'},
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
