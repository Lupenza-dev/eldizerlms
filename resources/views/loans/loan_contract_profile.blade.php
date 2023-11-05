@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
    .table th{
        background-color: #b5dddd;
        color: #2c2727
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
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" style="display: flex; flex-direction:row; justify-content: space-between; padding: 5px 5px 10px 5px">
                        <div>{{ date('d,M-Y')}}</div>
                        <div>
                            <h6 class="mb-0 text-uppercase text-center">Loan Applications</h6>
                        </div>
                        <div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-primary">Actions</button>
                                <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal"> <span class="bx bx-plus"></span> Add Repayment </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
                <table class="table table-bordered" style="width:100%">
                    <tbody>
                        <tr>
                            <th>Customer Name</th>
                            <td>{{ $contract->customer->first_name.' '.$contract->customer->last_name }}</td>
                            <th>Gender</th>
                            <td>{{ $contract->customer?->gender->name }}</td>
                            <td colspan="2" rowspan="5" class="text-center">
                                @if ($contract->customer->image)
                                <img style="height: 170px; width: 170px; border-radius: 50%; " src="{{ asset('storage').'/'.$contract->customer->image}}" alt="">   
                                @else
                                   <span class="bx bx-user-circle" style="font-size: 150px"></span> 
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Maritial Status</th>
                            <td>{{ $contract->customer?->marital_status?->name}}</td>
                            <th>DOB</th>
                            <td>{{ date('d,M-Y',strtotime($contract->customer->dob))}}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $contract->customer->phone_number}}</td>
                            <th>Email</th>
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
                        <tr>
                            <th>College Name</th>
                            <td>{{ $contract->customer?->student->college?->name }}</td>
                            <th>Study year</th>
                            <td>{{ $contract->customer?->student?->study_year}}</td>
                            <th>Student Reg Id</th>
                            <td>{{ $contract->customer?->student?->student_reg_id}}</td>
                        </tr>
                        <tr>
                            <th>Course Name</th>
                            <td>{{ $contract->customer?->student?->course}}</td>
                            <th>Position</th>
                            <td>Student</td>
                            <th>HESLB Benefeciary</th>
                            <td>{{ $contract->customer?->student?->heslb_status}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="col">
                    <h6 class="mb-0 text-uppercase text-center">Other Details</h6>
                    <hr/>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-file font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Loan Details</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-list-ol font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Payment Schedule</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-money font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Payments</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#bondTab" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-box font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Bond</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#agentTab" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-user font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Agent</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                                    <h6 class="mb-0 text-center">Loan Contract Details</h6>
                                    <table class="table table-bordered" style="width:100%; margin-top: 5px">
                                        <tbody>
                                            <tr>
                                                <th>Contract Code</th>
                                                <td>{{ $contract->contract_code}}</td>
                                                <th>Contract Status</th>
                                                <td>{{ $contract->status}}</td>
                                            </tr>
                                            <tr>
                                                <th>Start Date</th>
                                                <td>{{ date('d,M-Y',strtotime($contract->start_date))}}</td>
                                                <th>Expected End Date</th>
                                                <td>{{ date('d,M-Y',strtotime($contract->expected_end_date))}}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount</th>
                                                <td>{{ number_format($contract->amount) }}</td>
                                                <th>Total Loan Amount</th>
                                                <td>{{ number_format($contract->loan_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Total Paid In</th>
                                                <td>{{ number_format($contract->current_balance) }}</td>
                                                <th>Oustanding Amount</th>
                                                <td>{{ number_format($contract->outstanding_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <th>Plan</th>
                                                <td>{{ $contract->plan }}</td>
                                                <th>Installment Amount</th>
                                                <td>{{ number_format($contract->installment_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Interest Rate</th>
                                                <td>{{ $contract->interest_rate}}</td>
                                                <th>Interest Amount</th>
                                                <td>{{ number_format($contract->interest_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Past Due Days</th>
                                                <td>{{ number_format($contract->past_due_days)}}</td>
                                                <th>Past Due Amount</th>
                                                <td>{{ number_format($contract->past_due_amount) }}</td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <table class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Payment Date</th>
                                                <th>Amount</th>
                                                <th>Paid Amount</th>
                                                <th>OutStanding Amount</th>
                                                <th>Due Days</th>
                                                <th>Status</th>
                                            </tr>
                                            <tbody>
                                                @foreach ($contract->installments as $item)
                                                    <tr>
                                                        <td>{{ $item->installment_order }}</td>
                                                        <td>{{ $item->payment_date}}</td>
                                                        <td>{{ number_format($item->total_amount)}}</td>
                                                        <td>{{ number_format($item->current_balance)}}</td>
                                                        <td>{{ number_format($item->outstanding_amount)}}</td>
                                                        <td>{{ number_format($item->due_days)}}</td>
                                                        <td>{{ $item->status}}</td>
                                                    </tr> 
                                                @endforeach
                                            </tbody>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                    <table class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Payment Date</th>
                                                <th>Amount</th>
                                                <th>Payment Reference</th>
                                                <th>Payment Method</th>
                                                <th>Payment Channel</th>
                                                <th>Remark</th>
                                            </tr>
                                            <tbody>
                                                @foreach ($contract->payments as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item->payment_date}}</td>
                                                        <td>{{ number_format($item->amount)}}</td>
                                                        <td>{{ $item->payment_reference}}</td>
                                                        <td>{{ $item->payment_method}}</td>
                                                        <td>{{ $item->payment_channel}}</td>
                                                        <td>{{ $item->remarks}}</td>
                                                    </tr> 
                                                @endforeach
                                            </tbody>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="bondTab" role="tabpanel">
                                    <h6 class="mb-0 text-center">Loan Bond Details</h6>
                                    <table class="table table-bordered" style="width:100%; margin-top: 5px">
                                        <tbody>
                                            <tr>
                                                <th>Bond Name</th>
                                                <td>name</td>
                                                <th>Bond Type</th>
                                                <td>type</td>
                                                
                                            </tr>
                                            <tr>
                                                <th>Bond Value</th>
                                                <td>78888</td>
                                                <th>Remark</th>
                                                <td>Remark</td>
                                               
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>Status</td>
                                                <th>Desc</th>
                                                <td>Desc</td>
                                               
                                            </tr>
                                            <tr>
                                                <th>Image</th>
                                                <td>Remark</td>
                                                <th>Action</th>
                                                <td>Status</td>
                                            </tr>
                                            <tr>
                                                <th>Bond Reg Number</th>
                                                <td>6777777</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="agentTab" role="tabpanel">
                                    <h6 class="mb-0 text-center">Agent</h6>
                                    <table class="table table-bordered" style="width:100%; margin-top: 5px">
                                        <tbody>
                                            <tr>
                                                <th>Agent Name</th>
                                                <td>{{ $contract->loan_approval?->agent->name }}</td> 
                                                <th>Agent College</th>
                                                <td>{{ $contract->customer?->student->college?->name  }}</td>
                                                <th>Phone Number</th>
                                                <td>{{ $contract->loan_approval?->agent->phone_number}}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{{ $contract->loan_approval?->status }}</td>
                                                <th>Remark</th>
                                                <td>{{ $contract->loan_approval?->remark}}</td>
                                                <th>Attended Date</th>
                                                <td>{{ $contract->loan_approval?->attended_date ? date('d,M-Y',strtotime($contract->loan_approval?->attended_date)) : ""}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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