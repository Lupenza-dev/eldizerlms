@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Customers</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="bx bx-group text-white text-xl"></i>
                    </div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Customers</h6>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="filter-btn">
                        <i class="bx bx-filter text-lg"></i> Customer Filter
                    </button>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#registerCustomerModal">
                        <i class="bx bx-user-plus text-lg"></i> Register Customer
                    </button>
                    @endif
                </div>
            </div>

            {{-- Filter Panel --}}
            <form action="" id="submit-form" class="border-b border-slate-200 bg-slate-50 px-6 py-5" style="display:none">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Start Date</label>
                        <input type="date" name="start_date" class="form-control rounded-lg text-sm" value="{{ $requests['start_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">End Date</label>
                        <input type="date" name="end_date" class="form-control rounded-lg text-sm" value="{{ $requests['end_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="number" name="phone_number" class="form-control rounded-lg text-sm" value="{{ $requests['phone_number'] ?? null}}" placeholder="255*******">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">ID Number</label>
                        <input type="number" name="id_number" class="form-control rounded-lg text-sm" value="{{ $requests['id_number'] ?? null}}">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Reg ID</label>
                        <input type="text" name="student_reg_id" class="form-control rounded-lg text-sm" value="{{ $requests['student_reg_id'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Gender</label>
                        <select name="gender_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose Gender</option>
                            @if ($requests['gender_id'] ?? null)
                            <option value="1" {{ ($requests['gender_id'] == 1) ? "selected": null}}>Male</option>
                            <option value="2" {{ ($requests['gender_id'] == 2) ? "selected": null}}>Female</option>
                            @else
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                            @endif
                        </select>
                    </div>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                        <select name="college_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose College</option>
                            @foreach ($colleges as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Region</label>
                        <select name="region_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose Region</option>
                            @foreach ($regions as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-200">
                    <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="search-btn" type="submit">
                        <i class="bx bx-search"></i> Search
                    </button>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <button class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" formaction="{{ route('genderate.customer.report')}}">
                        <i class="bx bx-file"></i> Generate
                    </button>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="customers_table" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer Information</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Gender</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">ID Number</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer Type</th>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Roles</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Actions</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Manage Roles Modal --}}
<div class="modal fade" id="roleModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-user-voice text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Manage User Roles</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Customer Name</label>
                            <input type="text" id="name" class="form-control rounded-lg text-sm bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Customer Email</label>
                            <input type="text" id="email" class="form-control rounded-lg text-sm bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                            <select name="college_id" class="form-control rounded-lg text-sm">
                                <option value="">Please choose College</option>
                                @foreach ($colleges as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Roles</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($roles as $role)
                                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                    <input type="checkbox" name="role[]" value="{{ $role->id}}" class="rounded border-slate-300 text-blue-600">
                                    {{ $role->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div id="update_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Close
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
                            <i class="bx bx-save"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ════════ Register Customer Modal ════════ --}}
<div class="modal fade" id="registerCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-user-plus text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Register Customer</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" id="register_customer_form">
                    @csrf

                    {{-- Registration Type --}}
                    <div class="mb-4">
                        <h6 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 pb-2 border-b border-slate-200">
                            <i class="bx bx-user-check me-1"></i> Registration Type
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Customer Type <span class="text-red-500">*</span></label>
                                <select name="reg_type" id="reg_type" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select</option>
                                    <option value="1">Student</option>
                                    <option value="2">Intern</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Personal Info --}}
                    <div class="mb-4">
                        <h6 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 pb-2 border-b border-slate-200">
                            <i class="bx bx-user me-1"></i> Personal Information
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" class="form-control rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Middle Name <span class="text-red-500">*</span></label>
                                <input type="text" name="middle_name" class="form-control rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" class="form-control rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Other Name</label>
                                <input type="text" name="other_name" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                                <input type="text" name="phone_number" class="form-control rounded-lg text-sm"
                                       maxlength="9" minlength="9" pattern="\d{9}" placeholder="0712345678" required>
                                <p class="text-xs text-slate-400 mt-1">9 digits only — system will prepend `255`.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" class="form-control rounded-lg text-sm" required>
                            </div>
                            <div class="sm:col-span-2 lg:col-span-3">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">ID Number <span class="text-red-500">*</span></label>
                                <input type="text" name="id_number" class="form-control rounded-lg text-sm"
                                       maxlength="20" minlength="20" placeholder="20-digit ID number" required>
                                <p class="text-xs text-slate-400 mt-1">20-digit ID number.</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" name="dob" class="form-control rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Gender <span class="text-red-500">*</span></label>
                                <select name="gender_id" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select Gender</option>
                                    @foreach ($gender as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Marital Status</label>
                                <select name="marital_status_id" class="form-control rounded-lg text-sm">
                                    <option value="">Select</option>
                                    @foreach ($maritial_status as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <h6 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 pb-2 border-b border-slate-200">
                            <i class="bx bx-map me-1"></i> Address
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Region <span class="text-red-500">*</span></label>
                                <select name="region_id" id="reg_region" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select Region</option>
                                    @foreach ($regions as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">District <span class="text-red-500">*</span></label>
                                <select name="district_id" id="reg_district" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select District</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Ward <span class="text-red-500">*</span></label>
                                <select name="ward_id" id="reg_ward" class="form-control rounded-lg text-sm" required>
                                    <option value="">Select Ward</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Street <span class="text-red-500">*</span></label>
                                <input type="text" name="street" class="form-control rounded-lg text-sm" required>
                            </div>
                        </div>
                    </div>

                    {{-- Student Details (conditional) --}}
                    <div class="mb-4" id="student_section" style="display:none;">
                        <h6 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 pb-2 border-b border-slate-200">
                            <i class="bx bx-book me-1"></i> Student Details
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College <span class="text-red-500">*</span></label>
                                <select name="college_id" class="form-control rounded-lg text-sm">
                                    <option value="">Select College</option>
                                    @foreach ($colleges as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Course <span class="text-red-500">*</span></label>
                                <input type="text" name="course" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Reg ID <span class="text-red-500">*</span></label>
                                <input type="text" name="student_reg_id" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Form Four Index No <span class="text-red-500">*</span></label>
                                <input type="text" name="index_no" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Study Year <span class="text-red-500">*</span></label>
                                <input type="text" name="study_year" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">HESLB Status <span class="text-red-500">*</span></label>
                                <select name="heslb_status" class="form-control rounded-lg text-sm">
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ════════ Intern Details (conditional) ════════ --}}
                    <div class="mb-4" id="intern_section" style="display:none;">
                        <h6 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 pb-2 border-b border-slate-200">
                            <i class="bx bx-plus-medical me-1"></i> Intern Details
                        </h6>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Hospital <span class="text-red-500">*</span></label>
                                <select name="hospital_id" id="intern_hospital" class="form-control rounded-lg text-sm">
                                    <option value="">Select Hospital</option>
                                    @foreach ($hospitals as $h)
                                    <option value="{{ $h->id }}">{{ $h->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Professional <span class="text-red-500">*</span></label>
                                <select name="professional" id="intern_professional" class="form-control rounded-lg text-sm">
                                    <option value="">Select Professional</option>
                                    @foreach ($professionals as $p)
                                    <option value="{{ $p->name }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" id="intern_start_date" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">End Date <span class="text-red-500">*</span></label>
                                <input type="date" name="end_date" id="intern_end_date" class="form-control rounded-lg text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Letter (PDF/Image, max 2MB)</label>
                                <input type="file" name="letter" id="intern_letter"
                                       class="form-control rounded-lg text-sm"
                                       accept=".pdf,image/jpeg,image/png">
                                <p class="text-xs text-slate-400 mt-1">Recommendation letter from the hospital.</p>
                            </div>
                        </div>
                    </div>

                    <div id="register_alert"></div>

                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="register_btn">
                            <i class="bx bx-save"></i> Register Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Horizontal scroll — DataTables */
    .dataTables_wrapper .dataTables_scroll {
        overflow: hidden;
    }
    .dataTables_wrapper .dataTables_scrollBody {
        overflow-x: auto !important;
        overflow-y: hidden;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar {
        height: 10px;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 5px;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
    }
    .dataTables_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .dataTables_wrapper .dataTables_scrollHeadInner,
    .dataTables_wrapper .dataTables_scrollBody table {
        min-width: 1200px;
    }
</style>
@endpush

@push('scripts')
<script>
    $('#filter-btn').on('click', function () {
        $('#submit-form').toggle();
    });

    $(document).on('click', '.role-btn', function () {
        $('#id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#email').val($(this).data('email'));
    });
</script>

<script>
    $(document).ready(function () {
        var customersTable = $('#customers_table').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 500,
            scrollX: true,
            scrollCollapse: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('customers.data') }}",
                data: function (d) {
                    d.start_date     = $('#submit-form [name=start_date]').val();
                    d.end_date       = $('#submit-form [name=end_date]').val();
                    d.phone_number   = $('#submit-form [name=phone_number]').val();
                    d.id_number      = $('#submit-form [name=id_number]').val();
                    d.student_reg_id = $('#submit-form [name=student_reg_id]').val();
                    d.gender_id      = $('#submit-form [name=gender_id]').val();
                    d.college_id     = $('#submit-form [name=college_id]').val();
                    d.region_id      = $('#submit-form [name=region_id]').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex',   name: 'DT_RowIndex',   searchable: false, orderable: false},
                {data: 'reg_date',      name: 'created_at',    searchable: false},
                {data: 'customer_info', name: 'customer_info', searchable: true,  orderable: false},
                {data: 'gender',        name: 'gender',        searchable: false, orderable: false},
                {data: 'id_number',     name: 'id_number',     searchable: true,  orderable: false},
                {data: 'address',       name: 'address',       searchable: false, orderable: false},
                {data: 'customer_type', name: 'customer_type', searchable: false, orderable: false},
                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                {data: 'roles',         name: 'roles',         searchable: false, orderable: false},
                {data: 'actions',       name: 'actions',       searchable: false, orderable: false},
                @endif
            ],
            order: [[1, 'desc']],
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
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        $('#search-btn').on('click', function (e) {
            e.preventDefault();
            customersTable.ajax.reload();
        });

        // Toggle student / intern sections based on reg_type
        $('#reg_type').on('change', function () {
            const type = $(this).val();

            // Hide and clear both sections first
            $('#student_section').slideUp().find('input, select').val('');
            $('#intern_section').slideUp().find('input, select').val('');
            $('#intern_letter').val('');

            if (type === '1') {
                $('#student_section').slideDown();
            } else if (type === '2') {
                $('#intern_section').slideDown();
            }
        });

        // Region → District cascade
        $('#reg_region').on('change', function () {
            const regionId = $(this).val();
            $('#reg_district').html('<option value="">Loading...</option>');
            $('#reg_ward').html('<option value="">Select Ward</option>');

            if (!regionId) {
                $('#reg_district').html('<option value="">Select District</option>');
                return;
            }

            $.post("{{ route('customers.districts') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                region_id: regionId
            }, function (data) {
                let html = '<option value="">Select District</option>';
                data.forEach(d => html += `<option value="${d.id}">${d.name}</option>`);
                $('#reg_district').html(html);
            }).fail(() => {
                $('#reg_district').html('<option value="">Error loading</option>');
            });
        });

        // District → Ward cascade
        $('#reg_district').on('change', function () {
            const districtId = $(this).val();
            $('#reg_ward').html('<option value="">Loading...</option>');

            if (!districtId) {
                $('#reg_ward').html('<option value="">Select Ward</option>');
                return;
            }

            $.post("{{ route('customers.wards') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                district_id: districtId
            }, function (data) {
                let html = '<option value="">Select Ward</option>';
                data.forEach(w => html += `<option value="${w.id}">${w.name}</option>`);
                $('#reg_ward').html(html);
            }).fail(() => {
                $('#reg_ward').html('<option value="">Error loading</option>');
            });
        });

        // Register customer submit
        $('#register_customer_form').on('submit', function (e) {
            e.preventDefault();

            const btn = $('#register_btn');
            const alertDiv = $('#register_alert');
            const idle = '<i class="bx bx-save"></i> Register Customer';

            btn.html('<i class="bx bx-loader bx-spin me-1"></i> Registering...').prop('disabled', true);
            alertDiv.html('');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: new FormData(this),
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function (response) {
                    alertDiv.html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>${response.message || 'Customer registered successfully'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`);

                    setTimeout(() => {
                        $('#registerCustomerModal').modal('hide');
                        location.reload();
                    }, 1200);
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

                    alertDiv.html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i>${msgs}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`);
                },
                complete: function () {
                    btn.html(idle).prop('disabled', false);
                }
            });
        });

        // Reset modal on close
        $('#registerCustomerModal').on('hidden.bs.modal', function () {
            $('#register_customer_form')[0].reset();
            $('#reg_district').html('<option value="">Select District</option>');
            $('#reg_ward').html('<option value="">Select Ward</option>');
            $('#student_section').hide();
            $('#intern_section').hide();
            $('#register_alert').html('');
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#update_form').on('submit', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "{{ route('update.user.roles')}}",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    $('#update_alert').html('<div class="alert alert-success">' + response.message + '</div>');
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                },
                error: function (response) {
                    console.log(response.responseText);
                    if (jQuery.type(response.responseJSON.errors) == "object") {
                        $('#update_alert').html('');
                        $.each(response.responseJSON.errors, function (key, value) {
                            $('#update_alert').append('<div class="alert alert-danger">' + value + '</div>');
                        });
                    } else {
                        $('#update_alert').html('<div class="alert alert-danger">' + response.responseJSON.errors + '</div>');
                    }
                },
                beforeSend: function () {
                    $('#update_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Register .........');
                    $('#update_btn').attr('disabled', true);
                },
                complete: function () {
                    $('#update_btn').html('<i class="fa fa-save"></i> Register');
                    $('#update_btn').attr('disabled', false);
                }
            });
        });
    });
</script>
@endpush