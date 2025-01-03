@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">NMB Subscribers</div>
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
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
       
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase text-center">NMB Subscribers</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Account</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($subscribers as $subcriber)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d,M-Y',strtotime($subcriber->consent_request?->created_at))}}</td>
                                <td>{{ $subcriber->nmb_username }}</td>
                                <td>{{ $subcriber->consent_request?->from_account_number }}</td>
                                <td>{{ $subcriber->consent_request?->status }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit-btn"  data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                    data-id="{{ $subcriber->consent_request?->uuid}}" 
                                    title="Edit"> <i class="bx bx-edit"></i> </button>
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

<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="update_form">
                    <input type="hidden" name="uuid" id="id">
                    <div class="form-group row">
                        <div class="col-md-12 divider">
                            <label for="">Amount</label>
                            <input type="number"  name="amount" class="form-control" placeholder="Write Amount ......." required>
                        </div>
                        <div class="col-md-12 mt-2" id="update_alert">

                        </div>
                        <div class="col-md-12 divider mt-2" style="text-align:right">
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
    $('.edit-btn').on('click',function(){
        var id =$(this).data('id');
        $('#id').val(id);
      
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
      url:"{{ route('create.transaction')}}",
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
                   $('#update_btn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> loading .........');
                   $('#update_btn').attr('disabled', true);
              },
              complete : function(){
                $('#update_btn').html('<i class="fa fa-save"></i> Submit');
                $('#update_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
    
@endpush

