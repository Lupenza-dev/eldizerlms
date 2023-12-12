@extends('layouts.master')
@section('content')
<style>
    .danger{
        color: red;
    }
    label{
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
       
        <div class="card">
            <div class="card-body" >
                <div class="text-center">
                    <h6 class="mb-0 text-uppercase">Edit Customer Details</h6>
                </div>
                <hr>
                <form action="" id="update_form">
                    <input type="hidden"  name="customer_id" value="{{ $customer->uuid }}">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for=""> First Name <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="first_name" value="{{ $customer->first_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Middle Name <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="middle_name" value="{{ $customer->middle_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Last Name <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="last_name" value="{{ $customer->last_name }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Other Name <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="other_name" value="{{ $customer->other_name }}">
                        </div>
                    </div>
                    <div class="from-group row">
                        <div class="col-md-6">
                            <label for=""> Gender <span class="danger">*</span> </label>
                            <select name="gender" class="form-control">
                                <option value="{{ $customer->gender_id}}" selected>{{ $customer->gender?->name }}</option>
                                @foreach ($gender as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> Maritial Status <span class="danger">*</span> </label>
                            <select name="maritial_status" class="form-control">
                                <option value="{{ $customer->maritial_status_id}}" selected>{{ $customer->marital_status?->name }}</option>
                                @foreach ($maritial_status as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> DOB <span class="danger">*</span> </label>
                            <input type="date" name="dob" class="form-control" value="{{ $customer->dob}}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <h5>Customer Address</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for=""> Phone <span class="danger">*</span> </label>
                            <input type="number" name="phone" class="form-control" value="{{ $customer->phone_number}}" id="">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Email <span class="danger">*</span> </label>
                            <input type="email" name="email" class="form-control" value="{{ $customer->email}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for=""> Nida Number <span class="danger">*</span> </label>
                            <input type="number" name="id_number" class="form-control" value="{{ $customer->id_number}}" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for=""> Region <span class="danger">*</span> </label>
                            <select name="region_id" class="form-control">
                                <option value="{{ $customer->region_id}}" selected>{{ $customer->region?->name }}</option>
                                @foreach ($regions as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> District <span class="danger">*</span> </label>
                            <select name="district_id" class="form-control">
                                <option value="{{ $customer->district_id}}" selected>{{ $customer->district?->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> Ward <span class="danger">*</span> </label>
                            <select name="ward_id" class="form-control">
                                <option value="{{ $customer->ward_id}}" selected>{{ $customer->ward?->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> Street <span class="danger">*</span> </label>
                            <input type="text" name="street" class="form-control" value="{{ $customer->street}}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Residence Since <span class="danger">*</span> </label>
                            <input type="text" name="resident_since" class="form-control" value="{{ $customer->resident_since}}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <h5>Student Details</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for=""> College <span class="danger">*</span> </label>
                            <select name="college_id" class="form-control">
                                <option value="{{ $customer->student?->college_id}}" selected>{{ $customer->student?->college?->name }}</option>
                                @foreach ($colleges as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> Course <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="course" value="{{ $customer->student?->course }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Student Reg Id <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="student_reg_id" value="{{ $customer->student?->student_reg_id }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Form Four Index No <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="form_four_index_no" value="{{ $customer->student?->form_four_index_no }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Study Year <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="study_year" value="{{ $customer->student?->study_year }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Position <span class="danger">*</span> </label>
                            <input type="text" class="form-control" name="position" value="{{ $customer->student?->position }}">
                        </div>
                        <div class="col-md-6">
                            <label for=""> Heslb Status <span class="danger">*</span> </label>
                            <select name="heslb_status" class="form-control">
                                <option value="{{ $customer->student?->heslb_status}}" selected>{{ $customer->student?->heslb_status == 1 ? "Yes" :"No"}}</option>
                               <option value="1">Yes</option>
                               <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-12" id="update_alert"></div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('customers.index')}}">
                                <button class="btn btn-warning btn-sm" type="button"> <i class="bx bx-arrow-back"></i> Back</button>
                            </a>
                            <button class="btn btn-primary btn-sm" type="submit" id="update_btn">
                                <i class="bx bx-save"></i>Submit
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
    $(document).ready(function(){
      $('#update_form').on('submit',function(e){ 
          e.preventDefault();
      $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
          });
      $.ajax({
      type:'POST',
      url:"{{ route('update.customer')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#update_alert').html('<div class="alert alert-success">'+response.message+'</div>');
        setTimeout(function(){
         location.reload();
      },500);
      },
      error:function(response){
          console.log(response.responseText);
          if (jQuery.type(response.responseJSON.errors) == "object") {
            $('#update_alert').html('');
          $.each(response.responseJSON.errors,function(key,value){
              $('#update_alert').append('<div class="alert alert-danger">'+value+'</div>');
          });
          } else {
             $('#update_alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
          }
      },
      beforeSend : function(){
                   $('#update_btn').html('<i class="bx bx-spinner bx-pulse bx-spin"></i> Loading .........');
                   $('#update_btn').attr('disabled', true);
              },
              complete : function(){
                $('#update_btn').html('<i class="bx bx-save"></i> Save');
                $('#update_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
    
@endpush

