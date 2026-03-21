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
                <h5 class="mb-0 fw-bold text-primary">Loan Beneficiaries</h5>
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
                    <button type="button" class="btn btn-primary px-3 py-2" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                        <i class="bx bx-upload text-white me-2"></i>
                        <span class="text-white">Bulk Upload</span>
                    </button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
      
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">Beneficiaries Directory</h6>
                    <div class="badge bg-info">
                        <i class="bx bx-data me-1"></i>Server-Side Processing
                    </div>
                </div>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="student_table" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fullname</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Index Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year of Study</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Upload Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-upload me-2"></i>Bulk Upload Beneficiaries
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="registration_form">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="college_id" class="form-label fw-semibold">
                                <i class="bx bx-building me-2"></i>College Name
                            </label>
                            <select name="college_id" class="form-control" required>
                                <option value="">Select a college</option>
                                @foreach ($colleges as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="file" class="form-label fw-semibold">
                                <i class="bx bx-file me-2"></i>Excel File
                            </label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                            <small class="text-muted">Supported formats: Excel (.xlsx, .xls)</small>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Instructions:</strong> Download the sample format below to prepare your data correctly.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <i class="bx bx-download text-primary fs-4 me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">Sample Format</h6>
                                    <p class="mb-0 text-muted small">Download the Excel template to see the required format for uploading beneficiaries.</p>
                                </div>
                                <a href="{{ asset('assets/sample.xlsx') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-download me-1"></i>Download
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-12" id="alert"></div>
                        
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary" id="reg_btn">
                                <i class="bx bx-upload me-1"></i> Upload File
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
    // Form submission
    document.getElementById('registration_form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const regBtn = document.getElementById('reg_btn');
        const alertDiv = document.getElementById('alert');
        
        // Show loading state
        regBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Uploading...';
        regBtn.disabled = true;
        
        // Clear previous alerts
        alertDiv.innerHTML = '';
        
        const formData = new FormData(this);
        
        fetch("{{ route('beneficaries.store') }}", {
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
            regBtn.innerHTML = '<i class="bx bx-upload me-1"></i> Upload File';
            regBtn.disabled = false;
        });
    });
    
    // Initialize DataTable
    $('#student_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('beneficaries/data') }}",
        columns: [
            {data: 'full_name', name: 'full_name', visible: true, searchable: true, orderable: false},
            {data: 'index_number', name: 'index_number', visible: true, searchable: true,},
            {data: 'code', name: 'code', searchable: true, orderable: false, visible:true},
            {data: 'course_code', name: 'course_code', searchable: true, orderable: false, visible:true},
            {data: 'reg_no', name: 'reg_no', searchable: false, orderable: false, visible:true},
            {data: 'year_of_study', name: 'year_of_study', searchable: false, orderable: false, visible:true},            
            {data: 'academic_year', name: 'academic_year', searchable: false, orderable: false, visible:true},  
        ],
        language: {
            processing: '<i class="bx bx-loader bx-spin me-2"></i>Processing...',
            search: '<i class="bx bx-search me-2"></i>Search:',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            paginate: {
                first: '<i class="bx bx-chevrons-left"></i>',
                last: '<i class="bx bx-chevrons-right"></i>',
                next: '<i class="bx bx-chevron-right"></i>',
                previous: '<i class="bx bx-chevron-left"></i>'
            }
        },
        pageLength: 25,
        responsive: true,
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
              '<"row"<"col-sm-12"tr>>' +
              '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});
</script>
@endsection