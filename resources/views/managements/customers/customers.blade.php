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
    
    /* Enhanced Table Styles */
    .customers-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e8e8e8;
    }
    
    .customers-table thead {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }
    
    .customers-table th {
        padding: 1rem 0.75rem;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border: none;
        white-space: nowrap;
    }
    
    .customers-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .customers-table tbody tr:hover {
        background: #f8f9ff;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .customers-table td {
        padding: 0.875rem 0.75rem;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
    }
    
    .customer-info {
        line-height: 1.4;
    }
    
    .customer-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }
    
    .customer-contact {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.125rem;
    }
    
    .customer-contact i {
        color: #17a2b8;
        margin-right: 0.25rem;
        font-size: 0.75rem;
    }
    
    .gender-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .gender-male {
        background: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }
    
    .gender-female {
        background: #fce4ec;
        color: #c2185b;
        border: 1px solid #f8bbd9;
    }
    
    .id-badge {
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.85rem;
        color: #495057;
        border: 1px solid #dee2e6;
    }
    
    .college-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .role-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-right: 0.25rem;
        display: inline-block;
    }
    
    .role-admin {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .role-agent {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    
    .action-buttons .btn {
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .action-buttons .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .action-buttons .dropdown-item {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    
    .action-buttons .dropdown-item:hover {
        background: #f8f9ff;
        color: #17a2b8;
    }
    
    .action-buttons .dropdown-item i {
        margin-right: 0.5rem;
        color: #17a2b8;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .customers-table {
            font-size: 0.8rem;
        }
        
        .customers-table th {
            padding: 0.75rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .customers-table td {
            padding: 0.5rem;
        }
        
        .customer-name {
            font-size: 0.9rem;
        }
        
        .customer-contact {
            font-size: 0.7rem;
        }
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
                    <table id="example" class="table customers-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reg Date</th>
                                <th>Customer Information</th>
                                <th>Gender</th>
                                <th>ID Number</th>
                                <th>Address</th>
                                <th>College</th>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <th>Roles</th>
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td><strong>{{ $loop->iteration }}</strong></td>
                                <td>{{ date('d M Y',strtotime($customer->created_at))}}</td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ $customer->customer_name }}</div>
                                        <div class="customer-contact">
                                            <i class='bx bx-phone'></i>{{ $customer->phone_number }}
                                        </div>
                                        <div class="customer-contact">
                                            <i class='bx bx-envelope'></i>{{ $customer->email }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $genderClass = $customer->gender?->name == 'Male' ? 'gender-male' : 'gender-female';
                                    @endphp
                                    <span class="gender-badge {{ $genderClass }}">
                                        {{ $customer->gender?->name }}
                                    </span>
                                </td>
                                <td><span class="id-badge">{{ $customer->id_number }}</span></td>
                                <td>{!! $customer->address !!}</td>
                                <td>
                                    @if($customer->student?->college?->name)
                                        <span>{{ $customer->student->college->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                <td>
                                    @foreach ($customer->user?->roles ?? [] as $role)
                                        @php
                                            $roleClass = strtolower($role->name) == 'admin' ? 'role-admin' : 'role-agent';
                                        @endphp
                                        <span class="role-badge {{ $roleClass }}">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group action-buttons">
                                        <button type="button" class="btn btn-outline-primary btn-sm">Actions</button>
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item role-btn" data-bs-toggle="modal" data-bs-target="#roleModel" data-id="{{ $customer->id }}" data-name="{{ $customer->customer_name }}" data-email="{{ $customer->email }}">
                                                    <i class='bx bx-user-voice'></i> Manage Roles
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('customers.show',$customer->uuid) }}">
                                                    <i class='bx bx-user'></i> View Profile
                                                </a>
                                            </li>
                                            @if (Auth::user()->hasRole(['Admin','Super Admin']))
                                            <li>
                                                <a class="dropdown-item" href="{{ route('customers.edit',$customer->uuid) }}">
                                                    <i class='bx bx-edit'></i> Edit Customer
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
