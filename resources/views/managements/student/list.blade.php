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
            <div class="breadcrumb-title pe-3">Loan Beneficaries</div>
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-upload"></span> Upload </button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
      
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase text-center">Beneficaries</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="student_table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Fullname</th>
                                <th>Index Number</th>
                                <th>Code</th>
                                <th>Course Code</th>
                                <th>Reg No</th>
                                <th>Year of Study</th>
                                <th>Academic Year</th>
                                {{-- <th>College</th> --}}
                            </tr>
                        </thead>
                      
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
                <h5 class="modal-title">Bulk Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="registration_form">
                    <div class="form-group row">
                        <div class="col-md-12 divider">
                            <label for="">College Name</label>
                            <select name="college_id" class="form-control" required>
                                <option value="">Please Choose College Name</option>
                                @foreach ($colleges as $item)
                                    <option value="{{ $item->id}}">{{ $item->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">File</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-md-12 divider" id="alert" style="margin-top: 10px">
                        </div>
                        <div class="col-md-12 divider" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                            <button type="submit" class="btn btn-primary" id="reg_btn"> <span class="bx bx-save"></span> Submit</button>
                        </div>
                        <div class="col-md-12 divider">
                            <p>Download Sample Format used to upload <a href="{{ asset('assets/sample.xlsx')}}">Click Here</a></p>
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
      url:"{{ route('beneficaries.store')}}",
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
    $(document).ready(function() {
        $('#student_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('beneficaries/data')}}",
            columns: [
                {data: 'full_name', name: 'full_name', visible: true,searchable: true,orderable: false},
			{data: 'index_number', name: 'index_number', visible: true, searchable: true,},
			{data: 'code', name: 'code', searchable: true, orderable: false, visible:true},
			{data: 'course_code', name: 'course_code', searchable: true, orderable: false, visible:true},
            {data: 'reg_no', name: 'reg_no', searchable: false, orderable: false, visible:true},
            {data: 'year_of_study', name: 'year_of_study', searchable: false, orderable: false, visible:true},            
            {data: 'academic_year', name: 'academic_year', searchable: false, orderable: false, visible:true},  
            ]
        });
    });
</script>
@endpush