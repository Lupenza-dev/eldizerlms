@extends('layouts.master')
@section('content')
<style>
    .danger {
        color: red;
    }
    label {
        margin-top: 5px !important;
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Customers</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-0 text-uppercase">Edit Customer Details</h6>
                </div>
                <hr>
                <form action="" id="update_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->uuid }}">

                    {{-- Registration Type --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Customer Type <span class="danger">*</span></label>
                            <select name="reg_type" id="reg_type" class="form-control">
                                <option value="">Select</option>
                                <option value="1" {{ $customer->customer_type == 1 ? 'selected' : '' }}>Student</option>
                                <option value="2" {{ $customer->customer_type == 2 ? 'selected' : '' }}>Intern</option>
                            </select>
                        </div>
                    </div>

                    {{-- Personal Info --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">First Name <span class="danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="{{ $customer->first_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for="">Middle Name <span class="danger">*</span></label>
                            <input type="text" class="form-control" name="middle_name" value="{{ $customer->middle_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for="">Last Name <span class="danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="{{ $customer->last_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for="">Other Name</label>
                            <input type="text" class="form-control" name="other_name" value="{{ $customer->other_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Gender <span class="danger">*</span></label>
                            <select name="gender" class="form-control">
                                <option value="{{ $customer->gender_id }}" selected>{{ $customer->gender?->name }}</option>
                                @foreach ($gender as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">DOB <span class="danger">*</span></label>
                            <input type="date" name="dob" class="form-control" value="{{ $customer->dob }}">
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <h5>Customer Address</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Phone <span class="danger">*</span></label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ $customer->phone_number ? substr($customer->phone_number, -9) : '' }}"
                                   maxlength="9" pattern="\d{9}" placeholder="0712345678">
                            <small class="text-muted">9 digits only — system will prepend 255</small>
                        </div>
                        <div class="col-md-6">
                            <label for="">Email <span class="danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ $customer->email }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="">ID Number <span class="danger">*</span></label>
                            <input type="text" name="id_number" class="form-control"
                                   value="{{ $customer->id_number }}" maxlength="20" pattern="\d{20}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="">Region <span class="danger">*</span></label>
                            <select name="region_id" id="edit_region" class="form-control">
                                <option value="{{ $customer->region_id }}" selected>{{ $customer->region?->name }}</option>
                                @foreach ($regions as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">District <span class="danger">*</span></label>
                            <select name="district_id" id="edit_district" class="form-control">
                                <option value="{{ $customer->district_id }}" selected>{{ $customer->district?->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Ward <span class="danger">*</span></label>
                            <select name="ward_id" id="edit_ward" class="form-control">
                                <option value="{{ $customer->ward_id }}" selected>{{ $customer->ward?->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Street <span class="danger">*</span></label>
                            <input type="text" name="street" class="form-control" value="{{ $customer->street }}">
                        </div>
                        <!-- <div class="col-md-6">
                            <label for="">Residence Since</label>
                            <input type="text" name="resident_since" class="form-control" value="{{ $customer->resident_since }}">
                        </div> -->
                    </div>

                    {{-- Student Section (reg_type = 1) --}}
                    <div id="student_section" style="display:{{ $customer->customer_type == 1 ? 'block' : 'none' }};">
                        <div class="row mt-2">
                            <div class="col-md-12 text-center">
                                <h5>Student Details</h5>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">College <span class="danger">*</span></label>
                                <select name="college_id" class="form-control">
                                    <option value="{{ $customer->student?->college_id }}" selected>{{ $customer->student?->college?->name }}</option>
                                    @foreach ($colleges as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Course <span class="danger">*</span></label>
                                <input type="text" class="form-control" name="course" value="{{ $customer->student?->course }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Student Reg ID <span class="danger">*</span></label>
                                <input type="text" class="form-control" name="student_reg_id" value="{{ $customer->student?->student_reg_id }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Form Four Index No <span class="danger">*</span></label>
                                <input type="text" class="form-control" name="form_four_index_no" value="{{ $customer->student?->form_four_index_no }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Study Year <span class="danger">*</span></label>
                                <input type="text" class="form-control" name="study_year" value="{{ $customer->student?->study_year }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Position <span class="danger">*</span></label>
                                <input type="text" class="form-control" name="position" value="{{ $customer->student?->position }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">HESLB Status <span class="danger">*</span></label>
                                <select name="heslb_status" class="form-control">
                                    <option value="{{ $customer->student?->heslb_status }}" selected>{{ $customer->student?->heslb_status == 1 ? "Yes" : "No" }}</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Intern Section (reg_type = 2) --}}
                    <div id="intern_section" style="display:{{ $customer->customer_type == 2 ? 'block' : 'none' }};">
                        <div class="row mt-2">
                            <div class="col-md-12 text-center">
                                <h5>Intern Details</h5>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Hospital <span class="danger">*</span></label>
                                <select name="hospital_id" class="form-control">
                                    <option value="">Select Hospital</option>
                                    @foreach ($hospitals as $h)
                                        <option value="{{ $h->id }}" {{ $customer->intern?->hospital_id == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Professional <span class="danger">*</span></label>
                                <select name="professional" class="form-control">
                                    <option value="">Select Professional</option>
                                    @foreach ($professionals as $p)
                                        <option value="{{ $p->name }}" {{ $customer->intern?->professional == $p->name ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Start Date <span class="danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ $customer->intern?->start_date }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">End Date <span class="danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ $customer->intern?->end_date }}">
                            </div>
                            <div class="col-md-6">
                                <label for="">Letter (PDF/Image, max 5MB)</label>
                                <input type="file" name="letter" class="form-control" accept=".pdf,image/jpeg,image/png">
                                @if($customer->intern?->letter)
                                    <small class="text-muted d-block mt-1">
                                        Current: <a href="{{ asset('storage/' . $customer->intern->letter) }}" target="_blank">View letter</a>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-12" id="update_alert"></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('customers.index') }}">
                                <button class="btn btn-warning btn-sm" type="button"><i class="bx bx-arrow-back"></i> Back</button>
                            </a>
                            <button class="btn btn-primary btn-sm" type="submit" id="update_btn">
                                <i class="bx bx-save"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Toggle sections
        $('#reg_type').on('change', function () {
            const type = $(this).val();

            if (type === '1') {
                $('#student_section').slideDown();
                $('#intern_section').slideUp();
            } else if (type === '2') {
                $('#intern_section').slideDown();
                $('#student_section').slideUp();
            } else {
                $('#student_section').slideUp();
                $('#intern_section').slideUp();
            }
        });

        // Region → District cascade
        $('#edit_region').on('change', function () {
            const regionId = $(this).val();
            $('#edit_district').html('<option value="">Loading...</option>');
            $('#edit_ward').html('<option value="">Select Ward</option>');

            if (!regionId) {
                $('#edit_district').html('<option value="">Select District</option>');
                return;
            }

            $.post("{{ route('customers.districts') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                region_id: regionId
            }, function (data) {
                let html = '<option value="">Select District</option>';
                data.forEach(d => html += `<option value="${d.id}">${d.name}</option>`);
                $('#edit_district').html(html);
            });
        });

        // District → Ward cascade
        $('#edit_district').on('change', function () {
            const districtId = $(this).val();
            $('#edit_ward').html('<option value="">Loading...</option>');

            if (!districtId) {
                $('#edit_ward').html('<option value="">Select Ward</option>');
                return;
            }

            $.post("{{ route('customers.wards') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                district_id: districtId
            }, function (data) {
                let html = '<option value="">Select Ward</option>';
                data.forEach(w => html += `<option value="${w.id}">${w.name}</option>`);
                $('#edit_ward').html(html);
            });
        });

        // Submit
        $('#update_form').on('submit', function (e) {
            e.preventDefault();

            const btn = $('#update_btn');
            const alertDiv = $('#update_alert');
            const idle = '<i class="bx bx-save"></i> Submit';

            btn.html('<i class="bx bx-loader bx-spin"></i> Saving...').prop('disabled', true);
            alertDiv.html('');

            $.ajax({
                type: 'POST',
                url: "{{ route('update.customer') }}",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    alertDiv.html(`<div class="alert alert-success">
                        <i class="bx bx-check-circle me-2"></i>${response.message}
                    </div>`);
                    setTimeout(() => location.reload(), 800);
                },
                error: function (xhr) {
                    let msgs = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.values(xhr.responseJSON.errors).forEach(arr => {
                            arr.forEach(msg => { msgs += `<div>${msg}</div>`; });
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msgs = xhr.responseJSON.message;
                    } else {
                        msgs = 'An error occurred. Check Network tab.';
                    }
                    alertDiv.html(`<div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>${msgs}
                    </div>`);
                },
                complete: function () {
                    btn.html(idle).prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush