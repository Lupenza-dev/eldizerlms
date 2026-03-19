@extends('layouts.master')
@section('content')
<style>
    .customer-profile-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e8e8e8;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .section-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #17a2b8;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #17a2b8;
    }
    
    .info-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .info-table th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        font-size: 0.9rem;
        text-align: left;
        width: 25%;
    }
    
    .info-table td {
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        color: #2c3e50;
        font-weight: 500;
        background: #ffffff;
    }
    
    .info-table tr:hover td {
        background: #f8f9ff;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-active {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-student {
        background: #cce5ff;
        color: #004085;
        border: 1px solid #b3d7ff;
    }
    
    .customer-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #17a2b8;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #17a2b8;
        background: white;
        color: #17a2b8;
        font-size: 3rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .customer-id {
        background: #17a2b8;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .contact-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
    }
    
    .contact-info i {
        color: #17a2b8;
    }
    
    .college-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin: 1rem 0;
        border-left: 4px solid #17a2b8;
    }
    
    .college-details h6 {
        color: #17a2b8;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
        font-style: italic;
    }
    
    @media (max-width: 768px) {
        .info-table th,
        .info-table td {
            display: block;
            width: 100%;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .info-table th {
            background: #17a2b8;
            color: white;
            font-weight: 600;
        }
        
        .customer-profile-header {
            padding: 1.5rem;
        }
        
        .customer-avatar,
        .avatar-placeholder {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
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
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <!-- Header Section -->
                <div class="customer-profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-4">
                                {{-- @if($customer->image)
                                    <img src="{{ asset('storage').'/'.$customer->image}}" alt="Customer" class="customer-avatar">
                                @else --}}
                                    <div class="avatar-placeholder">
                                        {{ substr($customer->customer_name, 0, 1) }}
                                    </div>
                                {{-- @endif --}}
                                <div>
                                    <h3 class="mb-2">{{ $customer->customer_name }}</h3>
                                    <div class="contact-info mb-2">
                                        <i class="bx bx-phone"></i>
                                        <span>{{ $customer->phone_number }}</span>
                                    </div>
                                    <div class="contact-info mb-2">
                                        <i class="bx bx-envelope"></i>
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                    <div class="contact-info">
                                        <i class="bx bx-id-card"></i>
                                        <span>ID: {{ $customer->id_number }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <span class="customer-id">{{ $customer->id_number }}</span>
                            </div>
                            <div>
                                <span class="status-badge status-student">Student</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Personal Information Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-user"></i>
                        Personal Information
                    </h6>
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $customer->customer_name }}</td>
                                <th>Gender</th>
                                <td>{{ $customer->gender?->name }}</td>
                            </tr>
                            <tr>
                                <th>ID Number</th>
                                <td><span class="customer-id">{{ $customer->id_number }}</span></td>
                                <th>Date of Birth</th>
                                <td>{{ date('d M Y', strtotime($customer->dob)) }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $customer->phone_number }}</td>
                                <th>Email Address</th>
                                <td>{{ $customer->email }}</td>
                            </tr>
                            <tr>
                                <th>Marital Status</th>
                                <td>{{ $customer->marital_status?->name }}</td>
                                <th>Location</th>
                                <td>{{ $customer->address_location }}</td>
                            </tr>
                            <tr>
                                <th>Resident Since</th>
                                <td>{{ $customer->resident_since }}</td>
                                <th>Registration Date</th>
                                <td>{{ date('d M Y', strtotime($customer->created_at)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Academic Information Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-book"></i>
                        Academic Information
                    </h6>
                    @if($customer->student)
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <th>College Name</th>
                                    <td>{{ $customer->student?->college?->name }}</td>
                                    <th>Study Year</th>
                                    <td>{{ $customer->student?->study_year }}</td>
                                </tr>
                                <tr>
                                    <th>Student Reg ID</th>
                                    <td>{{ $customer->student?->student_reg_id }}</td>
                                    <th>Course</th>
                                    <td>{{ $customer->student?->course }}</td>
                                </tr>
                                <tr>
                                    <th>Form Four Index No</th>
                                    <td>{{ $customer->student?->form_four_index_no }}</td>
                                    <th>Position</th>
                                    <td><span class="status-badge status-student">{{ $customer->student?->position }}</span></td>
                                </tr>
                                <tr>
                                    <th>HESLB Status</th>
                                    <td colspan="3">{!! $customer->student?->heslb_status_formatted !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="bx bx-book" style="font-size: 2rem; color: #dee2e6;"></i>
                            <p class="mt-2">No academic information available</p>
                            <small class="text-muted">Academic details will be displayed here once added</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

