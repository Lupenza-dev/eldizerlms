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
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Agents</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-user-plus"></span> Add Agent</button>
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
      
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase text-center">Agents</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Reg Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>College</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($agents as $agent)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img style="height: 70px; width: 70px; border-radius: 50%" src="{{ asset('storage/attachments').'/'.$agent->image}}" alt=""></td>
                                <td>{{ date('d,M-Y',strtotime($agent->created_at))}}</td>
                                <td>{{ $agent->user?->name }} <br> {{ $agent->user?->phone_number }}</td>
                                <td>{{ $agent->user?->email }}</td>
                                <td>{{ $agent->student_reg_id }} <br>{{ $agent->college?->name }}</td>
                                <td>{!! $agent->user?->status_formatted !!}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-btn"  data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                        data-id="{{ $agent->uuid}}" 
                                        data-name ="{{ $agent->user?->name }}"
                                        data-email ="{{ $agent->user?->email}}"
                                        data-phone_number ="{{ $agent->user?->phone_number }}"
                                        data-student_reg ="{{ $agent->student_reg_id }}"
                                        data-college_id ="{{ $agent->college_id }}"
                                        title="Edit"> <i class="bx bx-edit"></i> </button>

                                        @if ($agent->user?->active == 2)
                                        <button class="btn btn-success btn-sm" id="{{ $agent->user?->uuid}}" onclick="enable_user(id)"><i class="bx bx-check text-white"></i></button>
                                        @else
                                        <button class="btn btn-warning btn-sm" id="{{ $agent->user?->uuid}}" onclick="deactivate_user(id)"><i class="bx bx-x text-white"></i></button>
                                        @endif
                                        <button class="btn btn-danger btn-sm" id="{{ $agent->user?->uuid}}" onclick="delete_user(id)"><i class="bx bx-trash text-white"></i></button>
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
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agent Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="registration_form">
                    <div class="form-group row">
                        <div class="col-md-12 divider">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Write fullname ......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Write Email......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Phone Number</label>
                            <input type="number" name="phone_number" class="form-control" placeholder="Write phone number......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Student Reg</label>
                            <input type="text" name="student_reg_id" class="form-control" placeholder="Write Student Reg Id......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">College</label>
                            <select name="college_id" class="form-control" required>
                                <option value="">Please Choose College</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id}}">{{ $college->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Agent Profile Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="col-md-12 divider" id="alert" style="margin-top: 10px">

                        </div>
                        <div class="col-md-12 divider" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                            <button type="submit" class="btn btn-primary" id="reg_btn"> <span class="bx bx-save"></span> Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agent Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group row">
                        <div class="col-md-12 divider">
                            <label for="">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Write fullname ......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Write Email......." readonly>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Phone Number</label>
                            <input type="number" id="phone_number" name="phone_number" class="form-control" placeholder="Write phone number......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Student Reg</label>
                            <input type="text" id="student_reg" name="student_reg_id" class="form-control" placeholder="Write Student Reg Id......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">College</label>
                            <select name="college_id" id="college_id" class="form-control" required>
                                <option value="">Please Choose College</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id}}">{{ $college->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-12 divider">
                            <label for="">Agent Profile Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div> --}}
                        <div class="col-md-12 divider" id="update_alert">

                        </div>
                        <div class="col-md-12 divider" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                            <button type="submit" class="btn btn-primary" id="update_btn"> <span class="bx bx-save"></span> Submit</button>
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
      $('#registration_form').on('submit',function(e){ 
          e.preventDefault();

      $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
          });
      $.ajax({
      type:'POST',
      url:"{{ route('agents.store')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#alert').html('<div class="alert alert-success">'+response.message+'</div>');
        setTimeout(function(){
         location.reload();
      },500);
      },
      error:function(response){
          console.log(response.responseText);
          if (jQuery.type(response.responseJSON.errors) == "object") {
            $('#alert').html('');
          $.each(response.responseJSON.errors,function(key,value){
              $('#alert').append('<div class="alert alert-danger">'+value+'</div>');
          });
          } else {
             $('#alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
          }
      },
      beforeSend : function(){
                   $('#reg_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Register .........');
                   $('#reg_btn').attr('disabled', true);
              },
              complete : function(){
                $('#reg_btn').html('<i class="fa fa-save"></i> Register');
                $('#reg_btn').attr('disabled', false);
              }
      });
  });
  });
</script>

<script>
      function enable_user(id){
      var csrf_tokken =$('meta[name="csrf-token"]').attr('content');
      swal({
      title: "Activate User",
      text: "Are you sure you want to Activate this User?",
      type: "success",
      showCancelButton: true,
      confirmButtonColor: "#0D6855",
      confirmButtonText: "Yes, Activate",
      closeOnConfirmation: false
    },
    function(){
      $.ajax({
            url: "{{ route('user.status')}}", 
            method: "POST",
            data: {uuid:id,'_token':csrf_tokken,action:'activate'},
            success: function(response)
           { 
           // console.log(response); 
            $.notify(response.message, "success");
            setTimeout(function(){
                location.reload();
            },500);
            },
            error: function(response){
               // console.log(response.responseText);
                $.notify(response.responseJson.errors,'error');  
            }
        });
    }
    );
  }

      function deactivate_user(id){
      var csrf_tokken =$('meta[name="csrf-token"]').attr('content');
      swal({
      title: "Deactivate User",
      text: "Are you sure you want to Deactivate this User?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#0D6855",
      confirmButtonText: "Yes, Deactivate",
      closeOnConfirmation: false
    },
    function(){
      $.ajax({
            url: "{{ route('user.status')}}", 
            method: "POST",
            data: {uuid:id,'_token':csrf_tokken,action:'deactivate'},
            success: function(response)
           { 
           // console.log(response); 
            $.notify(response.message, "success");
            setTimeout(function(){
                location.reload();
            },500);
            },
            error: function(response){
               // console.log(response.responseText);
                $.notify(response.responseJson.errors,'error');  
            }
        });
    }
    );
  }

  function delete_user(id){
      var csrf_tokken =$('meta[name="csrf-token"]').attr('content');
      swal({
      title: "Delete User",
      text: "Are you sure you want to Delete this User?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#0D6855",
      confirmButtonText: "Yes, Delete",
      closeOnConfirmation: false
    },
    function(){
      $.ajax({
            url: "{{ route('user.delete')}}", 
            method: "POST",
            data: {uuid:id,'_token':csrf_tokken,action:'deactivate'},
            success: function(response)
           { 
            $.notify(response.message, "success");
            setTimeout(function(){
                location.reload();
            },500);
            },
            error: function(response){
                $.notify(response.responseJson.errors,'error');  
            }
        });
    }
    );
  }
</script>
<script>
    $('.edit-btn').on('click',function(){
        var id =$(this).data('id');
        var name =$(this).data('name');
        var email =$(this).data('email');
        var phone_number =$(this).data('phone_number');
        var student_reg =$(this).data('student_reg');
        var college_id =$(this).data('college_id');

        $('#id').val(id);
        $('#name').val(name);
        $('#email').val(email);
        $('#phone_number').val(phone_number);
        $('#student_reg').val(student_reg);
        $('#college_id').val(college_id);
    })
</script>
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
      url:"{{ route('update.agent')}}",
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
                   $('#update_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Register .........');
                   $('#update_btn').attr('disabled', true);
              },
              complete : function(){
                $('#update_btn').html('<i class="fa fa-save"></i> Register');
                $('#update_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
    
@endpush