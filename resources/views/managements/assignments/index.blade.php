@extends('layouts.master')

@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Assignments</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-building"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <button type="button" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-toggle="modal" data-bs-target="#exampleLargeModal">
                    <i class="bx bx-plus"></i> Add Assignment
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="bx bx-task text-white text-xl"></i>
                </div>
                <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Assignments</h6>
            </div>
            <div class="p-6">
                <div class="table-responsive">
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Start Time</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">End Time</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Questions</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Participants</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($assignments as $assignment)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($assignment->created_at)) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-slate-800 whitespace-nowrap">{{ $assignment->name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{!! $assignment->start_time !!}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{!! $assignment->end_time !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('questions.list', $assignment) }}" class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="bx bx-list-ul"></i> Questions
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('participant.list', $assignment) }}" class="inline-flex items-center gap-1 bg-slate-600 hover:bg-slate-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="bx bx-group"></i> Participants
                                    </a>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{!! $assignment->status_formatted !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex gap-1">
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#exampleLargeModalEdit"
                                                data-id="{{ $assignment->uuid }}"
                                                data-name="{{ $assignment->name }}"
                                                data-location="{{ $assignment->location }}"
                                                data-rep_name="{{ $assignment->representative?->name }}"
                                                data-rep_phone="{{ $assignment->representative?->phone_number }}"
                                                data-rep_position="{{ $assignment->representative?->position }}"
                                                title="Edit">
                                            <i class="bx bx-edit text-sm"></i>
                                        </button>
                                        <button class="inline-flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors" id="{{ $assignment->uuid }}" onclick="delete_college(id)" title="Delete">
                                            <i class="bx bx-trash text-sm"></i>
                                        </button>
                                    </div>
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
<!-- Assignment Registration Modal -->
<div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-task text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Assignment Registration</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="registration_form">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Name</label>
                            <input type="text" name="name" class="form-control rounded-lg text-sm" placeholder="Enter assignment name..." required>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Start Time</label>
                                <input type="datetime-local" name="start_date" class="form-control rounded-lg text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">End Time</label>
                                <input type="datetime-local" name="end_date" class="form-control rounded-lg text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Total Questions</label>
                            <input type="number" name="total_questions" class="form-control rounded-lg text-sm" placeholder="Enter total questions..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Logo</label>
                            <input type="file" name="logo" class="form-control rounded-lg text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                            <select name="college_id" class="form-control rounded-lg text-sm">
                                <option value="">Please choose College</option>
                                @foreach ($colleges as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Close
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="reg_btn">
                            <i class="bx bx-save"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Assignment Modal -->
<div class="modal fade" id="exampleLargeModalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-600 to-cyan-800 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-edit text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Edit Assignment</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Name</label>
                            <input type="text" id="name" name="name" class="form-control rounded-lg text-sm" placeholder="Enter name..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Location</label>
                            <textarea name="location" id="location" class="form-control rounded-lg text-sm" placeholder="Enter location..."></textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Rep Name</label>
                                <input type="text" id="rep_name" name="rep_name" class="form-control rounded-lg text-sm" placeholder="Name..." required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Rep Phone</label>
                                <input type="text" id="rep_phone" name="rep_phone_number" class="form-control rounded-lg text-sm" placeholder="Phone..." required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Rep Position</label>
                                <input type="text" id="rep_position" name="position" class="form-control rounded-lg text-sm" placeholder="Position..." required>
                            </div>
                        </div>
                        <div id="update_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Close
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
                            <i class="bx bx-save"></i> Submit
                        </button>
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