@extends('layouts.master')
@section('content')

<style>
    .loan-profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        border-bottom: 3px solid #667eea;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #667eea;
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
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-approved {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-rejected {
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
        border: 3px solid #667eea;
        object-fit: cover;
    }
    
    .highlight-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #27ae60;
    }
    
    .loan-code {
        background: #667eea;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .guarantor-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .guarantor-table thead {
        background: #667eea;
        color: white;
    }
    
    .guarantor-table th {
        padding: 1rem;
        font-weight: 600;
        border: none;
    }
    
    .guarantor-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .guarantor-table tbody tr:hover {
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
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        
        .loan-profile-header {
            padding: 1.5rem;
        }
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Loan Application </div>
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
                <div class="loan-profile-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                {{-- @if($loan->customer?->image)
                                    <img src="{{ asset('storage').'/'.$loan->customer?->image}}" alt="Customer" class="customer-avatar">
                                @else --}}
                                    <div class="customer-avatar d-flex align-items-center justify-content-center" style="background: white; color: #667eea; font-size: 2rem; font-weight: bold;">
                                        {{ substr($loan->customer?->first_name ?? '', 0, 1) }}{{ substr($loan->customer?->last_name ?? '', 0, 1) }}
                                    </div>
                                {{-- @endif --}}
                                <div>
                                    <h4 class="mb-1">{{ ($loan->customer?->first_name ?? '').' '.($loan->customer?->last_name ?? '') }}</h4>
                                    <p class="mb-0 opacity-75">{{ $loan->customer?->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h5 class="mb-2">Loan Application</h5>
                            <span class="loan-code">{{ $loan->loan_code }}</span>
                            <div class="mt-2">
                                <small class="opacity-75">{{ $loan->created_at ? date('d M Y', strtotime($loan->created_at)) : 'N/A' }}</small>
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
                                            <button class="dropdown-item btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                                                <i class="bx bx-x me-2"></i> Reject Application
                                            </button>
                                        </li>
                                        @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                        <li>
                                            <button class="dropdown-item btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                                <i class="bx bx-check me-2"></i> Approve Application
                                            </button>
                                        </li>  
                                        @else
                                        <li>
                                            <button class="dropdown-item btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModalApprove">
                                                <i class="bx bx-check me-2"></i> Approve Application
                                            </button>
                                        </li> 
                                        @endif
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
                                <td>{{ ($loan->customer?->first_name ?? '').' '.($loan->customer?->last_name ?? '') }}</td>
                                <th>Gender</th>
                                <td>{{ $loan->customer?->gender_id ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>ID Number</th>
                                <td>{{ $loan->customer?->id_number ?? 'N/A'}}</td>
                                <th>Date of Birth</th>
                                <td>{{ $loan->customer?->dob ? date('d M Y',strtotime($loan->customer?->dob)) : 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $loan->customer?->phone_number ?? 'N/A'}}</td>
                                <th>Email Address</th>
                                <td>{{ $loan->customer?->email ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Region</th>
                                <td>{{ $loan->customer?->region?->name ?? 'N/A' }}</td>
                                <th>District</th>
                                <td>{{ $loan->customer?->district?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Ward</th>
                                <td>{{ $loan->customer?->ward?->name ?? 'N/A' }}</td>
                                <th>Resident Since</th>
                                <td>{{ $loan->customer?->resident_since ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- bank accounts info --}}
                   <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-user"></i>
                        Customer Bank and Mandate Status
                    </h6>
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Bank Name</th>
                                <td>{{ $loan->customer_mandate?->customer_bank_name }}</td>
                                <th>Bank Account Number</th>
                                <td>{{ $loan->customer_mandate?->customer_account_number }}</td>
                            </tr>
                            <tr>
                                <th>Mandate Status</th>
                                    <td>{{ $loan->customer_mandate?->status }}</td>
                                <th>Mandate Reference</th>
                                <td>{{ $loan->customer_mandate?->mandate_reference }}</td>
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
                                <td>{{ $loan->customer?->student?->college?->name ?? 'N/A' }}</td>
                                <th>Study Year</th>
                                <td>{{ $loan->customer?->student?->study_year ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Student Reg ID</th>
                                <td>{{ $loan->customer?->student?->student_reg_id ?? 'N/A'}}</td>
                                <th>Course</th>
                                <td>{{ $loan->customer?->student?->course ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td><span class="status-badge status-approved">Student</span></td>
                                <th>HESLB Beneficiary</th>
                                <td>{{ $loan->customer?->student?->heslb_status ?? 'N/A'}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- hos detail --}}
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-plus-medical"></i>
                        Hospital Information
                    </h6>
                    @if($loan->customer?->intern)
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <th>Hospital Name</th>
                                    <td>{{ $loan->customer?->intern?->hospital?->name }}</td>
                                    <th>Professional Title</th>
                                    <td>{{ $loan->customer?->intern?->professional }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date</th>
                                    <td>{{ $loan->customer?->intern?->start_date }}</td>
                                    <th>End Date</th>
                                    <td>{{ $loan->customer?->intern?->end_date }}</td>
                                </tr>
                                <tr>
                                    <th>Letter</th>
                                    <td colspan="3">
                                        @if($loan->customer?->intern?->letter)
                                            <a href="{{ asset('storage/' . $loan->customer?->intern?->letter) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                                <i class='bx bx-file'></i>
                                                View Letter
                                            </a>
                                        @else
                                            <span class="text-sm text-slate-400">No letter uploaded</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="bx bx-plus-medical" style="font-size: 2rem; color: #dee2e6;"></i>
                            <p class="mt-2">No Hospital information available</p>
                            <small class="text-muted">Hospital details will be displayed here once added</small>
                        </div>
                    @endif
                </div>
                <!-- Loan Application Details Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-dollar"></i>
                        Loan Application Details
                    </h6>
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Application Date</th>
                                <td>{{ $loan->created_at ? date('d M Y',strtotime($loan->created_at)) : 'N/A'}}</td>
                                <th>Loan Level</th>
                                <td>{!! $loan->level_formatted !!}</td>
                            </tr>
                            <tr>
                                <th>Loan Code</th>
                                <td><span class="loan-code">{{ $loan->loan_code }}</span></td>
                                <th>Payment Plan</th>
                                <td>{{ $loan->plan }}</td>
                            </tr>
                            <tr>
                                <th>Requested Amount</th>
                                <td class="highlight-amount">{{ number_format($loan->amount) }}</td>
                                <th>Approved Amount</th>
                                <td class="highlight-amount">{{ number_format($loan->loan_amount)}}</td>
                            </tr>
                            <tr>
                                <th>Installment Amount</th>
                                <td>{{ number_format($loan->installment_amount) }}</td>
                                <th>Interest Rate</th>
                                <td>{{ $loan->interest_rate}}%</td>
                            </tr>
                            <tr>
                                <th>Interest Amount</th>
                                <td>{{ number_format($loan->interest_amount) }}</td>
                                <th>Total Repayment</th>
                                <td class="highlight-amount">{{ number_format($loan->loan_amount + $loan->interest_amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Agent Information Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-user-voice"></i>
                        Agent Information
                    </h6>
                    <table class="info-table">
                        <tbody>
                            <tr>
                                <th>Agent Name</th>
                                <td>{{ $loan->loan_approval?->agent?->name ?: 'Not Assigned' }}</td>
                                <th>Agent College</th>
                                <td>{{ $loan->customer?->student?->college?->name  ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $loan->loan_approval?->agent?->phone_number ?: 'N/A' }}</td>
                                <th>Application Status</th>
                                <td>
                                    @php
                                        $status = $loan->loan_approval?->status;
                                        $statusClass = 'status-pending';
                                        if ($status == 'Approved') $statusClass = 'status-approved';
                                        elseif ($status == 'Rejected') $statusClass = 'status-rejected';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $status ?: 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Remark</th>
                                <td>{{ $loan->loan_approval?->remark ?: 'No remarks' }}</td>
                                <th>Attended Date</th>
                                <td>{{ $loan->loan_approval?->attended_date ? date('d M Y',strtotime($loan->loan_approval?->attended_date)) : 'Not attended' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Guarantor Details Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-group"></i>
                        Guarantor Details
                    </h6>
                    @if($loan->guarantors->count() > 0)
                        <div class="table-responsive guarantor-table">
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
                                    @foreach ($loan->guarantors as $item)
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
                <!-- Loan Bond Details Section -->
                <div class="info-card">
                    <h6 class="section-title">
                        <i class="bx bx-file"></i>
                        Loan Bond Details
                    </h6>
                    <div class="empty-state">
                        <i class="bx bx-file-blank" style="font-size: 2rem; color: #dee2e6;"></i>
                        <p class="mt-2">No bond details available yet</p>
                        <small class="text-muted">Bond information will be displayed here once added</small>
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
                    <input type="hidden" value="{{ $loan->uuid }}" name="loan_uuid">
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
                <h5 class="modal-title">Approve Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="approve_form">
                    <input type="hidden" value="{{ $loan->uuid }}" name="loan_uuid">
                    <div class="form-group row">
                        
                        <div class="col-md-12">
                            <label for="">Disbursment Date</label>
                            <input type="date" max="{{ date('Y-m-d')}}" class="form-control" name="payment_date" required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Disbursment Amount</label>
                            <input type="number" class="form-control" name="paid_amount" placeholder="Write Amount Disbursed...." required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Disbursment Reference</label>
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
                            <button type="submit" class="btn btn-info" id="approve_btn"> <span class="bx bx-save"></span> Approve</button>
                        </div>
                    </div>
                </form>
                
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="exampleLargeModalApprove" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="agent_approve_form">
                    <input type="hidden" value="{{ $loan->uuid }}" name="loan_uuid">
                    <div class="form-group row">
                        
                        <div class="col-md-12">
                            <label for="">Remark</label>
                           <textarea name="remark" class="form-control" placeholder="Write Remark...." required></textarea>
                        </div>
                        <div class="col-md-12" id="agent_approve_alert" style="margin-top: 10px">

                        </div>
                        <div class="col-md-12" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-x"></span> Close</button>
                            <button type="submit" class="btn btn-primary" id="agent_approve_btn"> <span class="bx bx-save text-white"></span> Approve</button>
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
      url:"{{ route('approve.loan.application')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#approve_alert').html('<div class="alert alert-success">'+response.message+'</div>');
        setTimeout(function(){
         location.reload();
      },500);
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

<script>
    $(document).ready(function(){
      $('#agent_approve_form').on('submit',function(e){ 
          e.preventDefault();
      $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
          });
      $.ajax({
      type:'POST',
      url:"{{ route('agent.approve.loan.application')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#agent_approve_alert').html('<div class="alert alert-success">'+response.message+'</div>');
        setTimeout(function(){
         location.reload();
      },500);
      },
      error:function(response){
          console.log(response.responseText);
          if (jQuery.type(response.responseJSON.errors) == "object") {
            $('#agent_approve_alert').html('');
          $.each(response.responseJSON.errors,function(key,value){
              $('#agent_approve_alert').append('<div class="alert alert-danger">'+value+'</div>');
          });
          } else {
             $('#agent_approve_alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
          }
      },
      beforeSend : function(){
                   $('#agent_approve_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Loading .........');
                   $('#agent_approve_btn').attr('disabled', true);
              },
              complete : function(){
                $('#agent_approve_btn').html('<i class="fa fa-save"></i> Reject');
                $('#agent_approve_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
    
@endpush