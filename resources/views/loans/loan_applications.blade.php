@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
    .custom-header{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-content: center
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Loan Applications</div>
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
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-user-plus"></span> Add Agent</button> --}}
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
                <div class="custom-header">
                    <div></div>
                    <h6 class="mb-0 text-uppercase">Loan Application</h6>
                    <div>
                        <button class="btn btn-info" id="filter-btn"><span style="color: #fff" class="bx bx-filter"></span> <span style="color: #fff">Custom Filter</span></button>
                    </div>
                </div>
                <form action="" id="submit-form" style="display: none">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="">Start Date</label>
                            <input type="date" name="application_start_date" class="form-control" value="{{ $requests['application_start_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="">End Date</label>
                            <input type="date" name="application_end_date" class="form-control" value="{{ $requests['application_end_date'] ?? null}}">
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
                        @endif
                    </div>
                    <div class="form-group row" style="margin-top: 10px">
                        <div class="col-md-3">
                            <label for="">Phone Number</label>
                            <input type="number" name="phone_number" class="form-control" value="{{ $requests['phone_number'] ?? null}}" placeholder="255*******">
                        </div>
                        <div class="col-md-3">
                            <label for="">ID Number</label>
                            <input type="number" name="id_number" class="form-control" value="{{ $requests['id_number'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="">Student Reg ID</label>
                            <input type="text" name="student_reg_id" class="form-control" value="{{ $requests['student_reg_id'] ?? null}}">
                        </div>
                        <div class="col-md-3" style="text-align: right; padding-top: 20px;">
                            <button class="btn btn-primary btn-sm" formaction="{{ route('loan.applications')}}" type="submit"><span class="bx bx-search"></span> Search</button>
                            @if (Auth::user()->hasRole(['Admin','Super Admin']))
                            <button class="btn btn-success btn-sm" formaction="{{ route('genderate.loan.application.report')}}"><span class="bx bx-file"></span> Generate </button>
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
                                <th>Application Date</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Amount</th>
                                <th>Total Loan</th>
                                <th>Installment</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($loans as $loan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d,M-Y',strtotime($loan->created_at))}}</td>
                                <td>{{ $loan->customer->first_name.' '.$loan->customer->last_name }}</td>
                                <td>{{ $loan->customer->email }} <br> {{ $loan->customer->phone_number }} </td>
                                <td>{{ number_format($loan->amount) }}</td>
                                <td>{{ number_format($loan->loan_amount) }}</td>
                                <td>{{ number_format($loan->installment_amount) }}</td>
                                <td>{{ $loan->plan }}</td>
                                <td>{!! $loan->level_formatted !!}</td>
                                <td>
                                    <a href="{{ route('loan.profile',$loan->uuid)}}">
                                    <button class="btn btn-primary btn-sm" title="User"> <i class="bx bx-user"></i> </button>
                                    </a>
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
{{-- <div class="modal fade" id="exampleLargeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agent Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="registration_form">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Write fullname ......." required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Write Email......." required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Phone Number</label>
                            <input type="number" name="phone_number" class="form-control" placeholder="Write phone number......." required>
                        </div>
                        <div class="col-md-12">
                            <label for="">Student Reg</label>
                            <input type="text" name="student_reg_id" class="form-control" placeholder="Write Student Reg Id......." required>
                        </div>
                        <div class="col-md-12">
                            <label for="">College</label>
                            <select name="college_id" class="form-control" required>
                                <option value="">Please Choose College</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id}}">{{ $college->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="">Agent Profile Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <div class="col-md-12" id="alert" style="margin-top: 10px">

                        </div>
                        <div class="col-md-12" style="text-align:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> <span class="bx bx-times"></span> Close</button>
                            <button type="submit" class="btn btn-primary" id="reg_btn"> <span class="bx bx-save"></span> Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
            
        </div>
    </div>
</div> --}}
    
@endsection
@push('scripts')
<script>
    $('#filter-btn').on('click',function(){
        $('#submit-form').toggle();
    })
</script>
    
@endpush

