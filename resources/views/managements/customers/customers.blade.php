@extends('layouts.master')
@section('content')
<style>
    .custom-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-content: center
    }
    .divider{
        margin-top: 10px !important;
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
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-user-plus"></span> Add User</button> --}}
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
            <div class="card-body" >
                <div class="custom-header">
                    <div></div>
                    <h6 class="mb-0 text-uppercase">Customers</h6>
                    <div>
                        <button class="btn btn-info" id="filter-btn"><span style="color: #fff" class="bx bx-filter"></span> <span style="color: #fff">Customer Filter</span></button>
                    </div>
                </div>
                <form action="" id="submit-form" style="display: none">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $requests['start_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $requests['end_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="">Phone Number</label>
                            <input type="number" name="phone_number" class="form-control" value="{{ $requests['phone_number'] ?? null}}" placeholder="255*******">
                        </div>
                        <div class="col-md-3">
                            <label for="">ID Number</label>
                            <input type="number" name="id_number" class="form-control" value="{{ $requests['id_number'] ?? null}}">
                        </div>
                    </div>
                    <div class="form-group row" style="margin-top: 10px">
                        <div class="col-md-3">
                            <label for="">Student Reg ID</label>
                            <input type="text" name="student_reg_id" class="form-control" value="{{ $requests['student_reg_id'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="">Gender</label>
                            <select name="gender_id" class="form-control">
                                <option value="">please choose Gender</option>
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
                        <div class="col-md-3">
                            <label for="">College</label>
                            <select name="college_id" class="form-control">
                                <option value="">please choose College</option>
                                @foreach ($colleges as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Region</label>
                            <select name="region_id" class="form-control">
                                <option value="">please choose Region</option>
                                @foreach ($regions as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    <div class="form-group row" style="margin-top: 10px">
                        <div class="col-md-12" style="text-align: right">
                            <button class="btn btn-primary btn-sm" formaction="{{ route('customers.index')}}" type="submit"><span class="bx bx-search"></span> Search</button>
                            @if (Auth::user()->hasRole(['Admin','Super Admin']))
                            <button class="btn btn-success btn-sm" formaction="{{ route('genderate.customer.report')}}"><span class="bx bx-file"></span> Generate </button>
                            @endif
                        </div>

                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reg Date</th>
                                <th>Customer Name</th>
                                <td>Gender</td>
                                <th>Id Number</th>
                                <th>Address</th>
                                <th>College</th>
                                <th>Role</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d,M-Y',strtotime($customer->created_at))}}</td>
                                <td>{{  $customer->customer_name }} <br>{{ $customer->phone_number}} <br> {{ $customer->email}} </td>
                                <td>{{ $customer->gender?->name }}</td>
                                <td>{{ $customer->id_number }}</td>
                                <td>{!! $customer->address !!}</td>
                                <td>{{ $customer->student?->college?->name}}</td>
                                <td>
                                    @foreach ($customer->user?->roles ?? [] as $role)
                                    {{ $role->name.' ,' }}
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary">Actions</button>
                                        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item role-btn" data-bs-toggle="modal" data-bs-target="#roleModel" data-id="{{ $customer->id }}" data-name="{{ $customer->customer_name}}" data-email="{{ $customer->email }}">Roles</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">Another action</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">Something else here</a>
                                            </li>
                                        </ul>
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
<div class="modal fade" id="roleModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="update_form">
                    <input type="hidden"  name="id" id="id">
                    <div class="form-group row">
                        <div class="col-md-12 divider">
                            <label for="">Customer Name</label>
                            <input type="text" name="" id="name" class="form-control" readonly>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">Customer Email</label>
                            <input type="text" name="" id="email" class="form-control" readonly>
                        </div>
                        <div class="col-md-12 divider">
                            <label for="">College</label>
                            <select name="college_id" class="form-control">
                                <option value="">please choose College</option>
                                @foreach ($colleges as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            @foreach ($roles as $role)
                            <input type="checkbox" class="divider" id="vehicle1" name="role[]" value="{{ $role->id}}">
                            <label for="role"> {{ $role->name }}</label><br>    
                            @endforeach
                        </div>
                       
                       
                        <div class="col-md-12 divider" id="update_alert" style="margin-top: 10px">

                        </div>
                    </div>
                    <div class="col-md-12 divider" style="text-align:right">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                        <button type="submit" class="btn btn-primary btn-sm"  id="update_btn"> <span class="bx bx-save"></span> Submit</button>
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
