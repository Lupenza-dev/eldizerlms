@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">
        {{-- Breadcrumb --}}
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-5">
            <div class="breadcrumb-title pe-3">
                <span class="text-lg font-bold text-slate-700">Customers</span>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" class="text-slate-400"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active text-slate-500" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                        <i class="bx bx-group text-white text-xl"></i>
                    </div>
                    <h6 class="text-sm font-semibold uppercase tracking-wider text-white mb-0">Customers</h6>
                </div>
                <button class="inline-flex items-center gap-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors" id="filter-btn">
                    <i class="bx bx-filter text-lg"></i> Customer Filter
                </button>
            </div>

            {{-- Filter Panel --}}
            <form action="" id="submit-form" class="border-b border-slate-200 bg-slate-50 px-6 py-5" style="display:none">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Start Date</label>
                        <input type="date" name="start_date" class="form-control rounded-lg text-sm" value="{{ $requests['start_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">End Date</label>
                        <input type="date" name="end_date" class="form-control rounded-lg text-sm" value="{{ $requests['end_date'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="number" name="phone_number" class="form-control rounded-lg text-sm" value="{{ $requests['phone_number'] ?? null}}" placeholder="255*******">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">ID Number</label>
                        <input type="number" name="id_number" class="form-control rounded-lg text-sm" value="{{ $requests['id_number'] ?? null}}">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Student Reg ID</label>
                        <input type="text" name="student_reg_id" class="form-control rounded-lg text-sm" value="{{ $requests['student_reg_id'] ?? null}}">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Gender</label>
                        <select name="gender_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose Gender</option>
                            @if ($requests['gender_id'] ?? null)
                            <option value="1" {{ ($requests['gender_id'] == 1) ? "selected": null}}>Male</option>
                            <option value="2" {{ ($requests['gender_id'] == 2) ? "selected": null}}>Female</option>
                            @else
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                            @endif
                        </select>
                    </div>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">College</label>
                        <select name="college_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose College</option>
                            @foreach ($colleges as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Region</label>
                        <select name="region_id" class="form-control rounded-lg text-sm">
                            <option value="">Please choose Region</option>
                            @foreach ($regions as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-slate-200">
                    <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="search-btn" type="submit">
                        <i class="bx bx-search"></i> Search
                    </button>
                    @if (Auth::user()->hasRole(['Admin','Super Admin']))
                    <button class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" formaction="{{ route('genderate.customer.report')}}">
                        <i class="bx bx-file"></i> Generate
                    </button>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="p-6">
                <div class="table-responsive">
                    <table id="customers_table" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer Information</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Gender</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">ID Number</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer Type</th>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Roles</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Actions</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Manage Roles Modal --}}
<div class="modal fade" id="roleModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bx bx-user-voice text-white text-lg"></i>
                    </div>
                    <h5 class="text-white font-semibold text-base mb-0">Manage User Roles</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <form action="" id="update_form">
                    <input type="hidden" name="id" id="id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Customer Name</label>
                            <input type="text" id="name" class="form-control rounded-lg text-sm bg-slate-50" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Customer Email</label>
                            <input type="text" id="email" class="form-control rounded-lg text-sm bg-slate-50" readonly>
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
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Roles</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($roles as $role)
                                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                    <input type="checkbox" name="role[]" value="{{ $role->id}}" class="rounded border-slate-300 text-blue-600">
                                    {{ $role->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div id="update_alert"></div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-slate-100">
                        <button type="button" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition-colors" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Close
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" id="update_btn">
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
    $('#filter-btn').on('click',function(){
        $('#submit-form').toggle();
    })

    $(document).on('click','.role-btn',function(){
        $('#id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#email').val($(this).data('email'));
    })
</script>
<script>
    $(document).ready(function(){
        var customersTable = $('#customers_table').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 500,
            ajax: {
                url: "{{ route('customers.data') }}",
                data: function (d) {
                    d.start_date     = $('#submit-form [name=start_date]').val();
                    d.end_date       = $('#submit-form [name=end_date]').val();
                    d.phone_number   = $('#submit-form [name=phone_number]').val();
                    d.id_number      = $('#submit-form [name=id_number]').val();
                    d.student_reg_id = $('#submit-form [name=student_reg_id]').val();
                    d.gender_id      = $('#submit-form [name=gender_id]').val();
                    d.college_id     = $('#submit-form [name=college_id]').val();
                    d.region_id      = $('#submit-form [name=region_id]').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false},
                {data: 'reg_date', name: 'created_at', searchable: false},
                {data: 'customer_info', name: 'customer_info', searchable: true, orderable: false},
                {data: 'gender', name: 'gender', searchable: false, orderable: false},
                {data: 'id_number', name: 'id_number', searchable: true, orderable: false},
                {data: 'address', name: 'address', searchable: false, orderable: false},
                {data: 'customer_type', name: 'customer_type', searchable: false, orderable: false},
                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                {data: 'roles', name: 'roles', searchable: false, orderable: false},
                {data: 'actions', name: 'actions', searchable: false, orderable: false},
                @endif
            ],
            order: [[1, 'desc']],
            language: {
                processing: '<i class="bx bx-loader bx-spin me-2"></i>Processing...',
                search: '<i class="bx bx-search me-2"></i>Search:',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                paginate: {
                    first: '<i class="bx bx-chevrons-left"></i>',
                    last: '<i class="bx bx-chevrons-right"></i>',
                    next: '<i class="bx bx-chevron-right"></i>',
                    previous: '<i class="bx bx-chevron-left"></i>'
                }
            },
            pageLength: 25,
            responsive: true,
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        $('#search-btn').on('click',function(e){
            e.preventDefault();
            customersTable.ajax.reload();
        });
    });
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
      url:"{{ route('update.user.roles')}}",
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
