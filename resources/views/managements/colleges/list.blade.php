@extends('layouts.master')

@section('content')
<style>
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
            <div class="breadcrumb-title pe-3">Universities</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-building"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-plus"></span> Add</button>
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
                <h6 class="mb-0 text-uppercase text-center">Universities</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Logo</th>
                                <th>Reg Date</th>
                                <th>Name</th>
                                <th>Representative</th>
                                {{-- <th>Phone Number</th> --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($colleges as $college)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img style="height: 50px; width: 50px" src="{{ asset('storage/attachments').'/'.$college->logo }}" alt=""></td>
                                <td>{{ date('d,M-Y',strtotime($college->created_at))}}</td>
                                <td>{{ $college->name }} <br> {{ $college->location}}</td>
                                <td>{!! $college->college_representative !!}</td>
                                <td>{!! $college->status_formatted !!}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-btn"  data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                     data-id="{{ $college->uuid}}" 
                                        data-name ="{{ $college->name}}"
                                        data-location ="{{ $college->location}}"
                                        data-rep_name ="{{ $college->representative?->name }}"
                                        data-rep_phone ="{{ $college->representative?->phone_number }}"
                                        data-rep_position ="{{ $college->representative?->position }}"
                                        title="Edit"> <i class="bx bx-edit"></i> </button>

                                        @if ($college->status == "Inactive")
                                        <button class="btn btn-success btn-sm" id="{{ $college->uuid}}" onclick="enable_college(id)"><i class="bx bx-check text-white"></i></button>
                                        @else
                                        <button class="btn btn-warning btn-sm" id="{{ $college->uuid}}" onclick="disable_college(id)"><i class="bx bx-x text-white"></i></button>
                                        @endif
                                        <button class="btn btn-danger btn-sm" id="{{ $college->uuid}}" onclick="delete_college(id)"><i class="bx bx-trash text-white"></i></button>
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
                <h5 class="modal-title">University Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="registration_form">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Write Name ......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Location</label>
                            <textarea name="location" class="form-control" placeholder="Write location..."></textarea>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Logo</label>
                            <input type="file" name="logo" class="form-control" required>
                        </div>
                        <div>
                            <h6 class="text-center" style="margin-top: 15px;">Representative</h6>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Representative Name</label>
                            <input type="text" name="rep_name" class="form-control" placeholder="Write representative name......" required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Representative Phone</label>
                            <input type="text" name="rep_phone_number" class="form-control" placeholder="Write representative name......" required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Representative Position</label>
                            <input type="text" name="position" class="form-control" placeholder="Write representative Position......" required>
                        </div>
                        <div class="col-md-12 divider" id="alert" style="margin-top: 10px">

                        </div>
                    </div>
                    <div class="col-md-12" style="text-align:right">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                        <button type="submit" class="btn btn-primary" id="reg_btn"> <span class="bx bx-save"></span> Submit</button>
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
                <h5 class="modal-title">University Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Write Name ......." required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Location</label>
                            <textarea name="location" id="location" class="form-control" placeholder="Write location..."></textarea>
                        </div>
                        {{-- <div class="col-md-12 divider">
                            <label for="">Logo</label>
                            <input type="file" name="logo" class="form-control" required>
                        </div> --}}
                        <div>
                            <h6 class="text-center" style="margin-top: 15px;">Representative</h6>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Representative Name</label>
                            <input type="text" id="rep_name" name="rep_name" class="form-control" placeholder="Write representative name......" required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Representative Phone</label>
                            <input type="text" id="rep_phone" name="rep_phone_number" class="form-control" placeholder="Write representative name......" required>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Representative Position</label>
                            <input type="text" id="rep_position" name="position" class="form-control" placeholder="Write representative Position......" required>
                        </div>
                        <div class="col-md-12 divider" id="update_alert">

                        </div>
                    </div>
                    <div class="col-md-12" style="text-align:right">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                        <button type="submit" class="btn btn-primary" id="update_btn"> <span class="bx bx-save"></span> Submit</button>
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
      url:"{{ route('colleges.store')}}",
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
      function enable_college(id){
      var csrf_tokken =$('meta[name="csrf-token"]').attr('content');
      swal({
      title: "Activate College",
      text: "Are you sure you want to Activate this College ?",
      type: "success",
      showCancelButton: true,
      confirmButtonColor: "#0D6855",
      confirmButtonText: "Yes, Activate",
      closeOnConfirmation: false
    },
    function(){
      $.ajax({
            url: "{{ route('college.status')}}", 
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

      function disable_college(id){
      var csrf_tokken =$('meta[name="csrf-token"]').attr('content');
      swal({
      title: "Deactivate College",
      text: "Are you sure you want to Deactivate this College?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#0D6855",
      confirmButtonText: "Yes, Deactivate",
      closeOnConfirmation: false
    },
    function(){
      $.ajax({
            url: "{{ route('college.status')}}", 
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

      function delete_college(id){
      var csrf_tokken =$('meta[name="csrf-token"]').attr('content');
      swal({
      title: "Delete College",
      text: "Are you sure you want to Delete this College?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#0D6855",
      confirmButtonText: "Yes, Delete",
      closeOnConfirmation: false
    },
    function(){
      $.ajax({
            url: "{{ route('college.delete')}}", 
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
        var location =$(this).data('location');
        var rep_name =$(this).data('rep_name');
        var rep_phone =$(this).data('rep_phone');
        var rep_position =$(this).data('rep_position');

        $('#id').val(id);
        $('#name').val(name);
        $('#location').val(location);
        $('#rep_name').val(rep_name);
        $('#rep_phone').val(rep_phone);
        $('#rep_position').val(rep_position);
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
      url:"{{ route('update.college')}}",
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