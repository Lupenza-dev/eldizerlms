@extends('layouts.master')
@section('content')
<style>
    .contract-profile-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        border-bottom: 3px solid #28a745;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #28a745;
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
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-overdue {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .action-buttons .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .customer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid #28a745;
        object-fit: cover;
    }
    
    .highlight-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #28a745;
    }
    
    .highlight-negative {
        font-size: 1.2rem;
        font-weight: 700;
        color: #dc3545;
    }
    
    .contract-code {
        background: #28a745;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .nav-tabs .nav-link {
        border: none;
        background: #f8f9fa;
        color: #495057;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 8px 8px 0 0;
        margin-right: 0.25rem;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link.active {
        background: #28a745;
        color: white;
        border: none;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        background: #e9ecef;
        color: #28a745;
    }
    
    .tab-content {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0 0 12px 12px;
        padding: 1.5rem;
    }
    
    .payment-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .payment-table thead {
        background: #28a745;
        color: white;
    }
    
    .payment-table th {
        padding: 1rem;
        font-weight: 600;
        border: none;
    }
    
    .payment-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .payment-table tbody tr:hover {
        background: #f8f9ff;
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
            background: #28a745;
            color: white;
            font-weight: 600;
        }
        
        .contract-profile-header {
            padding: 1.5rem;
        }
        
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Loan Contract</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">profile</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-user-plus"></span> Add Agent</button> --}}
                    {{-- <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
                        <a class="dropdown-item" href="javascript:;">Another action</a>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                        <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div> --}}
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        {{-- <hr/> --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <!-- Header Section -->
                <div class="contract-profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-3">
                               
                                    <div class="customer-avatar d-flex align-items-center justify-content-center" style="background: white; color: #28a745; font-size: 2rem; font-weight: bold;">
                                        {{ substr($contract->customer->first_name, 0, 1) }}{{ substr($contract->customer->last_name, 0, 1) }}
                                    </div>
                                <div>
                                    <h4 class="mb-1">{{ $contract->customer->first_name.' '.$contract->customer->last_name }}</h4>
                                    <p class="mb-0 opacity-75">{{ $contract->customer->phone_number }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h5 class="mb-2">Loan Contract</h5>
                            <span class="contract-code">{{ $contract->contract_code }}</span>
                            <div class="mt-2">
                                <span class="status-badge status-active">{{ $contract->status }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="action-buttons">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light">Actions</button>
                                    <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                                <i class="bx bx-plus me-2"></i> Add Repayment
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Customer Information Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-user"></i>
                        Customer Information
                    </h6>
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $contract->customer->first_name.' '.$contract->customer->last_name }}</td>
                                <th>Gender</th>
                                <td>{{ $contract->customer?->gender?->name }}</td>
                            </tr>
                            <tr>
                                <th>ID Number</th>
                                <td>{{ $contract->customer->id_number}}</td>
                                <th>Date of Birth</th>
                                <td>{{ date('d M Y',strtotime($contract->customer->dob))}}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $contract->customer->phone_number}}</td>
                                <th>Email Address</th>
                                <td>{{ $contract->customer->email}}</td>
                            </tr>
                            <tr>
                                <th>Region</th>
                                <td>{{ $contract->customer->region?->name }}</td>
                                <th>District</th>
                                <td>{{ $contract->customer->district?->name }}</td>
                            </tr>
                            <tr>
                                <th>Ward</th>
                                <td>{{ $contract->customer->ward?->name }}</td>
                                <th>Resident Since</th>
                                <td>{{ $contract->customer->resident_since }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Student Details Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-book"></i>
                        Student Details
                    </h6>
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>College Name</th>
                                <td>{{ $contract->customer?->student->college?->name }}</td>
                                <th>Study Year</th>
                                <td>{{ $contract->customer?->student?->study_year}}</td>
                            </tr>
                            <tr>
                                <th>Student Reg ID</th>
                                <td>{{ $contract->customer?->student?->student_reg_id}}</td>
                                <th>Course</th>
                                <td>{{ $contract->customer?->student?->course}}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td><span class="status-badge status-active">Student</span></td>
                                <th>HESLB Beneficiary</th>
                                <td>{{ $contract->customer?->student?->heslb_status}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Tabbed Details Section -->
                <div class="info-card">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                    <i class='bx bx-file font-18 me-1'></i>
                                    Loan Details
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                    <i class='bx bx-list-ol font-18 me-1'></i>
                                    Payment Schedule
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false">
                                    <i class='bx bx-money font-18 me-1'></i>
                                    Payments
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#bondTab" role="tab" aria-selected="false">
                                    <i class='bx bx-box font-18 me-1'></i>
                                    Bond
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#agentTab" role="tab" aria-selected="false">
                                    <i class='bx bx-user font-18 me-1'></i>
                                    Agent
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#guarantorTab" role="tab" aria-selected="false">
                                    <i class='bx bx-group font-18 me-1'></i>
                                    Guarantors
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                                <h6 class="section-title mb-3">
                                    <i class="bx bx-dollar"></i>
                                    Contract Financial Details
                                </h6>
                                <table class="info-table">
                                    <tbody>
                                        <tr>
                                            <th>Contract Code</th>
                                            <td><span class="contract-code">{{ $contract->contract_code}}</span></td>
                                            <th>Contract Status</th>
                                            <td><span class="status-badge status-active">{{ $contract->status}}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Start Date</th>
                                            <td>{{ date('d M Y',strtotime($contract->start_date))}}</td>
                                            <th>Expected End Date</th>
                                            <td>{{ date('d M Y',strtotime($contract->expected_end_date))}}</td>
                                        </tr>
                                        <tr>
                                            <th>Original Amount</th>
                                            <td>{{ number_format($contract->amount) }}</td>
                                            <th>Total Loan Amount</th>
                                            <td class="highlight-amount">{{ number_format($contract->loan_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Paid In</th>
                                            <td class="highlight-amount">{{ number_format($contract->current_balance) }}</td>
                                            <th>Outstanding Amount</th>
                                            <td class="highlight-negative">{{ number_format($contract->outstanding_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Plan</th>
                                            <td>{{ $contract->plan }}</td>
                                            <th>Installment Amount</th>
                                            <td>{{ number_format($contract->installment_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Interest Rate</th>
                                            <td>{{ $contract->interest_rate}}%</td>
                                            <th>Interest Amount</th>
                                            <td>{{ number_format($contract->interest_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Past Due Days</th>
                                            <td>
                                                @if($contract->past_due_days > 0)
                                                    <span class="status-badge status-overdue">{{ number_format($contract->past_due_days) }} days</span>
                                                @else
                                                    <span class="status-badge status-active">On Time</span>
                                                @endif
                                            </td>
                                            <th>Past Due Amount</th>
                                            <td class="highlight-negative">{{ number_format($contract->past_due_amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                <h6 class="section-title mb-3">
                                    <i class="bx bx-calendar"></i>
                                    Payment Schedule
                                </h6>
                                @if($contract->installments->count() > 0)
                                    <div class="table-responsive payment-table">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Date</th>
                                                    <th>Amount</th>
                                                    <th>Paid Amount</th>
                                                    <th>Outstanding</th>
                                                    <th>Due Days</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($contract->installments as $item)
                                                    <tr>
                                                        <td>{{ $item->installment_order }}</td>
                                                        <td>{{ date('d M Y', strtotime($item->payment_date)) }}</td>
                                                        <td>{{ number_format($item->total_amount)}}</td>
                                                        <td class="highlight-amount">{{ number_format($item->current_balance)}}</td>
                                                        <td class="highlight-negative">{{ number_format($item->outstanding_amount)}}</td>
                                                        <td>
                                                            @if($item->due_days > 0)
                                                                <span class="status-badge status-overdue">{{ number_format($item->due_days) }}</span>
                                                            @else
                                                                <span class="status-badge status-active">0</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusClass = 'status-pending';
                                                                if (strtolower($item->status) == 'paid') $statusClass = 'status-active';
                                                                elseif (strtolower($item->status) == 'overdue') $statusClass = 'status-overdue';
                                                            @endphp
                                                            <span class="status-badge {{ $statusClass }}">{{ $item->status }}</span>
                                                        </td>
                                                    </tr> 
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="bx bx-calendar-x" style="font-size: 2rem; color: #dee2e6;"></i>
                                        <p class="mt-2">No payment schedule available</p>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                <h6 class="section-title mb-3">
                                    <i class="bx bx-receipt"></i>
                                    Payment History
                                </h6>
                                @if($contract->payments->count() > 0)
                                    <div class="table-responsive payment-table">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Payment Date</th>
                                                    <th>Amount</th>
                                                    <th>Reference</th>
                                                    <th>Method</th>
                                                    <th>Channel</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($contract->payments as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ date('d M Y', strtotime($item->payment_date)) }}</td>
                                                        <td class="highlight-amount">{{ number_format($item->amount)}}</td>
                                                        <td><span class="contract-code">{{ $item->payment_reference}}</span></td>
                                                        <td>{{ $item->payment_method}}</td>
                                                        <td>{{ $item->payment_channel}}</td>
                                                        <td>{{ $item->remarks ?: '-' }}</td>
                                                    </tr> 
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="bx bx-money" style="font-size: 2rem; color: #dee2e6;"></i>
                                        <p class="mt-2">No payments recorded yet</p>
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="bondTab" role="tabpanel">
                                <h6 class="section-title mb-3">
                                    <i class="bx bx-file"></i>
                                    Bond Details
                                </h6>
                                <div class="empty-state">
                                    <i class="bx bx-file-blank" style="font-size: 2rem; color: #dee2e6;"></i>
                                    <p class="mt-2">No bond details available yet</p>
                                    <small class="text-muted">Bond information will be displayed here once added</small>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="agentTab" role="tabpanel">
                                <h6 class="section-title mb-3">
                                    <i class="bx bx-user-voice"></i>
                                    Agent Information
                                </h6>
                                <table class="info-table">
                                    <tbody>
                                        <tr>
                                            <th>Agent Name</th>
                                            <td>{{ $contract->loan_approval?->agent?->name ?: 'Not Assigned' }}</td>
                                            <th>Agent College</th>
                                            <td>{{ $contract->customer?->student?->college?->name  ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number</th>
                                            <td>{{ $contract->loan_approval?->agent?->phone_number ?: 'N/A' }}</td>
                                            <th>Application Status</th>
                                            <td>
                                                @php
                                                    $status = $contract->loan_approval?->status;
                                                    $statusClass = 'status-pending';
                                                    if ($status == 'Approved') $statusClass = 'status-active';
                                                    elseif ($status == 'Rejected') $statusClass = 'status-overdue';
                                                @endphp
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ $status ?: 'Pending' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Remark</th>
                                            <td>{{ $contract->loan_approval?->remark ?: 'No remarks' }}</td>
                                            <th>Attended Date</th>
                                            <td>{{ $contract->loan_approval?->attended_date ? date('d M Y',strtotime($contract->loan_approval?->attended_date)) : 'Not attended' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="guarantorTab" role="tabpanel">
                                <h6 class="section-title mb-3">
                                    <i class="bx bx-group"></i>
                                    Guarantor Details
                                </h6>
                                @if($contract->guarantors->count() > 0)
                                    <div class="table-responsive payment-table">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Guarantor Name</th>
                                                    <th>Relationship</th>
                                                    <th>Phone Number</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($contract->guarantors as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>{{ ucwords($item->full_name)}}</td>
                                                        <td>{{ ucwords($item->relationship)}}</td>
                                                        <td>{{ $item->phone_number }}</td>
                                                    </tr>  
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="empty-state">
                                        <i class="bx bx-info-circle" style="font-size: 2rem; color: #dee2e6;"></i>
                                        <p class="mt-2">No guarantors added yet</p>
                                    </div>
                                @endif
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
               
               
               
              
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="reject_form">
                    <input type="hidden" value="{{ $contract->uuid }}" name="loan_uuid">
                    <div class="form-group row">
                        
                        <div class="col-md-12">
                            <label for="">Rejection Reason</label>
                           <textarea name="remark" class="form-control" placeholder="Write rejection reason...." required></textarea>
                        </div>
                        <div class="col-md-12" id="reject_alert" style="margin-top: 10px">

                        </div>
                        <div class="col-md-12" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-x"></span> Close</button>
                            <button type="submit" class="btn btn-warning" id="reject_btn"> <span class="bx bx-save"></span> Reject</button>
                        </div>
                    </div>
                </form>
                
            </div>
            
        </div>
    </div>
</div>
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Loan Repayment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="approve_form">
                    <input type="hidden" value="{{ $contract->uuid }}" name="contract_uuid">
                    <div class="form-group row">
                        
                        <div class="col-md-12">
                            <label for="">Payment Date</label>
                            <input type="date" max="{{ date('Y-m-d')}}" class="form-control" name="payment_date" required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Paid Amount</label>
                            <input type="number" class="form-control" name="paid_amount" placeholder="Write Amount Paid...." required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Payment Reference</label>
                            <input type="text" class="form-control" name="payment_reference" placeholder="Write Payment Reference.." required>
                        </div>
                            <div class="col-md-12">
                                <label for="">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="" selected>Payment method</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Mobile Money">Mobile Money</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="">Payment Channel</label>
                                <select name="payment_channel" class="form-control" required>
                                    <option value="" selected>Payment Channel</option>
                                    <optgroup label="Bank Transfer" id="bank_list" style="display: none">
                                      <option value="CRDB">CRDB</option>
                                      <option value="NMB">NMB</option>
                                      <option value="NBC">NBC</option>
                                      <option value="EQUITY">EQUITY</option>
                                    </optgroup>
                                    <optgroup label="Mobile Money" id="mobile_money_list" style="display: none">
                                      <option value="Airtel Money">Airtel Money</option>
                                      <option value="M-pesa">M-pesa</option>
                                      <option value="Tigo pesa">Tigo pesa</option>
                                      <option value="Halo pesa">Halo pesa</option>
                                    </optgroup>
                                  </select>
                            </div>
                        <div class="col-md-12" id="approve_alert" style="margin-top: 10px">

                        </div>
                        <div class="col-md-12" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-x"></span> Close</button>
                            <button type="submit" class="btn btn-info" id="approve_btn"> <span class="bx bx-save"></span> Submit</button>
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
    $(document).ready(function(){
      $('#reject_form').on('submit',function(e){ 
          e.preventDefault();

      $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
          });
      $.ajax({
      type:'POST',
      url:"{{ route('reject.loan.application')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#reject_alert').html('<div class="alert alert-success">'+response.message+'</div>');
        setTimeout(function(){
         location.reload();
      },500);
      },
      error:function(response){
          console.log(response.responseText);
          if (jQuery.type(response.responseJSON.errors) == "object") {
            $('#reject_alert').html('');
          $.each(response.responseJSON.errors,function(key,value){
              $('#reject_alert').append('<div class="alert alert-danger">'+value+'</div>');
          });
          } else {
             $('#reject_alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
          }
      },
      beforeSend : function(){
                   $('#reject_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Loading .........');
                   $('#reject_btn').attr('disabled', true);
              },
              complete : function(){
                $('#reject_btn').html('<i class="fa fa-save"></i> Reject');
                $('#reject_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
<script>
     $('#payment_method').on('change',function(){
    var value =$(this).val();
    if (value == "Bank") {
            $('#bank_list').css('display','');
            $('#mobile_money_list').css('display','none');
    } else if(value == "Mobile Money"){
        $('#bank_list').css('display','none');
        $('#mobile_money_list').css('display','');
    } 
    else {
        $('#bank_list').css('display','none');
        $('#mobile_money_list').css('display','none');
    }
   });
    $(document).ready(function(){
      $('#approve_form').on('submit',function(e){ 
          e.preventDefault();

      $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
          });
      $.ajax({
      type:'POST',
      url:"{{ route('loan.repayment')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#approve_alert').html('<div class="alert alert-success">'+response.message+'</div>');
    //     setTimeout(function(){
    //      location.reload();
    //   },500);
      },
      error:function(response){
          console.log(response.responseText);
          if (jQuery.type(response.responseJSON.errors) == "object") {
            $('#approve_alert').html('');
          $.each(response.responseJSON.errors,function(key,value){
              $('#approve_alert').append('<div class="alert alert-danger">'+value+'</div>');
          });
          } else {
             $('#approve_alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
          }
      },
      beforeSend : function(){
                   $('#approve_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Loading .........');
                   $('#approve_btn').attr('disabled', true);
              },
              complete : function(){
                $('#approve_btn').html('<i class="bx bx-check"></i> Approve');
                $('#approve_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
    
@endpush