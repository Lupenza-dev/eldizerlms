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
            <div class="breadcrumb-title pe-3">Loan Contracts</div>
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
               <h6 class="mb-0 text-uppercase text-center">Loan Contracts</h6>
               <hr>
               <form action="" style="margin-bottom: 30px;">
                <div class="form-group row">
                    <div class="col-md-3">
                        <label for="">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $requests['start_date'] ?? null}}">
                    </div>
                    <div class="col-md-3">
                        <label for="">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $requests['end_date'] ?? null}}">
                    </div>
                    <div class="col-md-3">
                        <label for="">Loan Code</label>
                        <input type="text" class="form-control" name="contract_code" placeholder="Write Loan Code" value="{{ $requests['contract_code'] ?? null}}">
                    </div>
                    <div class="col-md-3">
                        <label for="">Loan Contract Status</label>
                        <select name="loan_status" class="form-control" >
                            <option value="" selected>Please choose Loan Contract Status</option>
                            <option value="GRANTED">GRANTED</option>
                            <option value="CLOSED">CLOSED</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" style="margin-top: 10px;">
                    <div class="col-md-3">
                        <label for="">Past Due Days</label>
                        <select name="loan_status" class="form-control" >
                            <option value="" selected>Please choose Past Due Days</option>
                            <option value="0-30">0-30</option>
                            <option value="31-60">31-60</option>
                            <option value="61-90">61-90</option>
                            <option value="90 +">90 +</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">Phone Number</label>
                        <input type="number" class="form-control" name="phone_number" value="{{ $requests['phone_number'] ?? null}}"  placeholder="Write phone number (2557*****)">
                    </div>
                    <div class="col-md-3">
                        <label for="">University</label>
                        <select name="loan_status" class="form-control" >
                            <option value="" selected>Please choose University</option>
                            @foreach ($universities as $item)
                              <option value="{{ $item->id}}">{{ $item->name }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">Student Reg ID</label>
                        <input type="name" class="form-control" value="{{ $requests['student_reg_id'] ?? null}}" name="student_reg_id" placeholder="Write Student Reg ID">
                    </div>
                </div>
                <div class="form-group row" style="margin-top: 10px;">
                    <div class="col-md-12 text-center">
                        <button formaction="{{ route('loan.contracts') }}" class="btn btn-primary"> <span class="bx bx-search"></span> Search </button>
                        <button formaction="{{ route('generate.loan.contracts') }}" class="btn btn-success"> <span class="bx bx-file"></span> Generate Excel </button>
                    </div>

                </div>
               </form>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Amount</th>
                                <th>Total Loan</th>
                                <th>Paid Amount</th>
                                <th>Outstanding Amount</th>
                                {{-- <th>Plan</th> --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($contracts as $contract)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d,M-Y',strtotime($contract->start_date))}}</td>
                                <td>{{ date('d,M-Y',strtotime($contract->expected_end_date))}}</td>
                                <td>{{ $contract->customer->first_name.' '.$contract->customer->middle_name.' '.$contract->customer->last_name }}</td>
                                <td>{{ $contract->customer->email }} <br> {{ $contract->customer->phone_number }} </td>
                                <td>{{ number_format($contract->amount) }}</td>
                                <td>{{ number_format($contract->loan_amount) }}</td>
                                <td>{{ number_format($contract->current_balance) }}</td>
                                <td>{{ number_format($contract->outstanding_amount) }}</td>
                                <td>{{ $contract->status }}</td>
                                <td>
                                    <a href="{{ route('loan.contract.profile',$contract->uuid)}}">
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
