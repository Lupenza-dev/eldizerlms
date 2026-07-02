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
                    <button class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors" formaction="{{ route('customers.index')}}" type="submit">
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
                    <table id="example" class="table w-full" style="width:100%">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600">
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Reg Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Customer Information</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Gender</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">ID Number</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Address</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">College</th>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Roles</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($customers as $customer)
                            <tr class="border-t border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-semibold text-slate-700 whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">{{ date('d M Y', strtotime($customer->created_at)) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-semibold text-slate-800">{{ $customer->customer_name }}</span>
                                        <span class="text-xs text-slate-500 flex items-center gap-1">
                                            <i class="bx bx-phone text-cyan-500"></i>{{ $customer->phone_number }}
                                        </span>
                                        <span class="text-xs text-slate-500 flex items-center gap-1">
                                            <i class="bx bx-envelope text-cyan-500"></i>{{ $customer->email }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($customer->gender?->name == 'Male')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                            <i class="bx bx-male mr-1"></i> Male
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-700 border border-pink-200">
                                            <i class="bx bx-female mr-1"></i> {{ $customer->gender?->name ?? 'N/A' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">{{ $customer->id_number }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500">{!! $customer->address !!}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($customer->student?->college?->name)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-700">
                                            {{ $customer->student->college->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">N/A</span>
                                    @endif
                                </td>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @foreach ($customer->user?->roles ?? [] as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mr-1
                                            {{ strtolower($role->name) == 'admin' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-cyan-100 text-cyan-700 border border-cyan-200' }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="btn-group">
                                        <button type="button" class="inline-flex items-center gap-1 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium px-3 py-1.5 rounded-l-lg transition-colors">Actions</button>
                                        <button type="button" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1.5 rounded-r-lg border-l border-slate-600 dropdown-toggle dropdown-toggle-split transition-colors" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu shadow-lg border-0 rounded-xl overflow-hidden">
                                            <li>
                                                <a class="dropdown-item flex items-center gap-2 py-2 text-sm role-btn" data-bs-toggle="modal" data-bs-target="#roleModel" data-id="{{ $customer->id }}" data-name="{{ $customer->customer_name }}" data-email="{{ $customer->email }}">
                                                    <i class="bx bx-user-voice text-cyan-500"></i> Manage Roles
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="{{ route('customers.show',$customer->uuid) }}">
                                                    <i class="bx bx-user text-blue-500"></i> View Profile
                                                </a>
                                            </li>
                                            @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                            <li>
                                                <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="{{ route('customers.edit',$customer->uuid) }}">
                                                    <i class="bx bx-edit text-emerald-500"></i> Edit Customer
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
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

    $('.role-btn').on('click',function(){
        $('#id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#email').val($(this).data('email'));
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
