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
            <div class="breadcrumb-title pe-3">Loan Application</div>
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
                                        <button class="dropdown-item btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-x"></span> Reject Application </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal"> <span class="bx bx-check"></span> Approve Application </button>
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
                            <td>{{ $loan->customer->first_name.' '.$loan->customer->last_name }}</td>
                            <th>Gender</th>
                            <td>{{ $loan->customer->gender?->name }}</td>
                            <td colspan="2" rowspan="5" class="text-center">
                                <img style="height: 170px; width: 170px; border-radius: 50%; " src="{{ asset('storage').'/'.$loan->customer->image}}" alt="">   
                            </td>
                        </tr>
                        <tr>
                            <th>Maritial Status</th>
                            <td>{{ $loan->customer->marital_status?->name}}</td>
                            <th>DOB</th>
                            <td>{{ date('d,M-Y',strtotime($loan->customer->dob))}}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $loan->customer->phone_number}}</td>
                            <th>Email</th>
                            <td>{{ $loan->customer->email}}</td>
                           
                        </tr>
                        <tr>
                            <th>Region</th>
                            <td>{{ $loan->customer->region->name }}</td>
                            <th>District</th>
                            <td>{{ $loan->customer->district->name }}</td>
                        </tr>
                        <tr>
                            <th>Ward</th>
                            <td>{{ $loan->customer->ward->name }}</td>
                            <th>Resident Since</th>
                            <td>{{ $loan->customer->resident_since }}</td>
                        </tr>
                    </tbody>
                </table>
                <h6 class="mb-0 text-uppercase text-center">Student Details</h6>
                <hr/>
                <table class="table table-bordered" style="width:100%">
                    <tbody>
                        <tr>
                            <th>College Name</th>
                            <td>{{ $loan->customer?->student->college?->name }}</td>
                            <th>Study year</th>
                            <td>{{ $loan->customer?->student?->study_year}}</td>
                            <th>Student Reg Id</th>
                            <td>{{ $loan->customer?->student?->student_reg_id}}</td>
                        </tr>
                        <tr>
                            <th>Course Name</th>
                            <td>{{ $loan->customer?->student?->course}}</td>
                            <th>Position</th>
                            <td>Student</td>
                            <th>HESLB Benefeciary</th>
                            <td>{{ $loan->customer?->student?->heslb_status}}</td>
                        </tr>
                    </tbody>
                </table>
                <h6 class="mb-0 text-uppercase text-center">Loan Application Details</h6>
                <hr/>
                <table class="table table-bordered" style="width:100%">
                    <tbody>
                        <tr>
                            <th>Application Date</th>
                            <td>{{ date('d,M-Y',strtotime($loan->created_at))}}</td>
                            <th>Level</th>
                            <td>{{ $loan->level }}</td>
                            <th>Loan Code</th>
                            <td>{{ $loan->loan_code }}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td>{{ number_format($loan->amount) }}</td>
                            <th>Loan Amount</th>
                            <td>{{ number_format($loan->loan_amount)}}</td>
                            <th>Plan</th>
                            <td>{{ $loan->plan }}</td>
                        </tr>
                        <tr>
                            <th>Installment Amount</th>
                            <td>{{ number_format($loan->installment_amount) }}</td>
                            <th>Interes rate</th>
                            <td>{{ $loan->interest_rate}}</td>
                            <th>Interest Amount</th>
                            <td>{{ number_format($loan->interest_amount) }}</td>
                        </tr>
                    </tbody>
                </table>
                <h6 class="mb-0 text-uppercase text-center">Agent</h6>
                <hr/>
                <table class="table table-bordered" style="width:100%">
                    <tbody>
                        <tr>
                            <th>Agent Name</th>
                            <td>{{ $loan->loan_approval->agent->name }}</td>
                            <th>Agent College</th>
                            <td>{{ $loan->customer?->student->college?->name  }}</td>
                            <th>Phone Number</th>
                            <td>{{ $loan->loan_approval->agent->phone_number}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $loan->loan_approval->status }}</td>
                            <th>Remark</th>
                            <td>{{ $loan->loan_approval->remark}}</td>
                            <th>Attended Date</th>
                            <td>{{ $loan->loan_approval->attended_date ? date('d,M-Y',strtotime($loan->loan_approval->attended_date)) : ""}}</td>
                        </tr>
                    </tbody>
                </table>
                <h6 class="mb-0 text-uppercase text-center">Loan Bond Details</h6>
                <hr/>
                <table class="table table-bordered" style="width:100%">
                    <tbody>
                        <tr>
                            <th>Bond Name</th>
                            <td>name</td>
                            <th>Bond Type</th>
                            <td>type</td>
                            <th>Bond Reg Number</th>
                            <td>6777777</td>
                        </tr>
                        <tr>
                            <th>Bond Value</th>
                            <td>78888</td>
                            <th>Remark</th>
                            <td>Remark</td>
                            <th>Status</th>
                            <td>Status</td>
                        </tr>
                        <tr>
                            <th>Desc</th>
                            <td>Desc</td>
                            <th>Image</th>
                            <td>Remark</td>
                            <th>Action</th>
                            <td>Status</td>
                        </tr>
                        
                    </tbody>
                </table>
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
    
@endpush