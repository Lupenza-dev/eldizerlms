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
                <form id="registration_form" class="form-repeater">
                    <div id="alert"></div>
                    <input type="hidden" value="{{$assignment->id}}" name="assignment_id">
                    <div data-repeater-list="questions">
                        <div data-repeater-item>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="name">Question</label>
                                    <textarea name="name" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-5 mb-2">
                                    <label for="choices">Choices (comma separated)</label>
                                    <input type="text" name="choices" class="form-control" placeholder="e.g. Choice 1, Choice 2" />
                                </div>

                                <div class="col-md-5 mb-2">
                                    <label for="correct_answer">Correct Answer</label>
                                    <input type="text" name="correct_answer" class="form-control" placeholder="e.g. Choice 1" />
                                </div>
                                
                                <div class="col-md-2">
                                    <button data-repeater-delete type="button" class="btn btn-danger" style="margin-top: 28px;">Delete</button>
                                </div>
                            </div>
                            <hr/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button data-repeater-create type="button" class="btn btn-primary">Add Question</button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" id="reg_btn" class="btn btn-success"><i class="fa fa-save"></i> Save Questions</button>
                        </div>
                    </div>
                </form>
               
            </div>
        </div>
    </div>
</div>
    
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script>
    $(document).ready(function () {
        'use strict';
        $('.form-repeater').repeater({
            isFirstItemUndeletable: true,
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });
    });
</script>

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
      url:"{{ route('questions.store')}}",
      data : new FormData(this),
      contentType: false,
      cache: false,
      processData : false,
      success:function(response){
        console.log(response);
        $('#alert').html('<div class="alert alert-success">'+response.message+'</div>');
        setTimeout(function(){
         window.location.href = "{{ route('questions.list', $assignment) }}";
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
                $('#reg_btn').html('<i class="fa fa-save"></i> Save Questions');
                $('#reg_btn').attr('disabled', false);
              }
      });
  });
  });
</script>
@endpush