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
            <div class="breadcrumb-title pe-3">Assigments Questions</div>
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
                    <a href="{{ route('question.create',$assignment)}}">
                     <button type="button" class="btn btn-primary"> <span class="bx bx-plus"></span> Add</button>
                    </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase text-center">Assigments Questions</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reg Date</th>
                                <th>Name</th>
                                <th>Choices</th>
                                <th>Answer</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($questions as $question)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d,M-Y',strtotime($question->created_at))}}</td>
                                <td>{{ $question->name }}</td>
                                <td>{!! $question->choices !!}</td>
                                <td>{!! $question->correct_answer !!}</td>
                                <td>{!! $question->status_formatted !!}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-btn"  data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                     data-id="{{ $question->uuid}}" 
                                        data-name ="{{ $question->name}}"
                                        title="Edit"> <i class="bx bx-edit"></i> </button>

                                        {{-- @if ($college->status == "Inactive")
                                        <button class="btn btn-success btn-sm" id="{{ $college->uuid}}" onclick="enable_college(id)"><i class="bx bx-check text-white"></i></button>
                                        @else
                                        <button class="btn btn-warning btn-sm" id="{{ $college->uuid}}" onclick="disable_college(id)"><i class="bx bx-x text-white"></i></button>
                                        @endif --}}
                                        {{-- <button class="btn btn-danger btn-sm" id="{{ $assignment->uuid}}" onclick="delete_college(id)"><i class="bx bx-trash text-white"></i></button> --}}
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
      url:"{{ route('assignments.store')}}",
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