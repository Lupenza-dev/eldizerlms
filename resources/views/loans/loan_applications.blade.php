@extends('layouts.master')
@section('content')
<style>
    td{
        align-content: center;
    }
</style>
<div class="page-wrapper bg-light">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
            <div class="breadcrumb-title pe-3">
                <h5 class="mb-0 fw-bold text-primary">Loan Applications</h5>
            </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;" class="text-muted text-decoration-none">
                                <i class="bx bx-home-alt text-primary"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group shadow-sm">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-user-plus"></span> Add Agent</button> --}}
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
      
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0 text-uppercase fw-bold text-dark">Loan Applications</h6>
                    <div>
                        <button class="btn btn-info px-3 py-2 shadow-sm" id="filter-btn">
                            <i class="bx bx-filter text-white me-2"></i>
                            <span class="text-white">Custom Filter</span>
                        </button>
                    </div>
                </div>
                
                <form action="" id="submit-form" class="bg-light p-4 rounded-3 mb-4" style="display: none">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">Start Date</label>
                            <input type="date" name="application_start_date" class="form-control" value="{{ $requests['application_start_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">End Date</label>
                            <input type="date" name="application_end_date" class="form-control" value="{{ $requests['application_end_date'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">Gender</label>
                            <select name="gender_id" class="form-control">
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
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">College</label>
                            <select name="college_id" class="form-control">
                                <option value="">Please choose College</option>
                                @foreach ($colleges as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>  
                        @endif
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">Phone Number</label>
                            <input type="number" name="phone_number" class="form-control" value="{{ $requests['phone_number'] ?? null}}" placeholder="255*******">
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">ID Number</label>
                            <input type="number" name="id_number" class="form-control" value="{{ $requests['id_number'] ?? null}}">
                        </div>
                        <div class="col-md-3">
                            <label for="" class="form-label fw-semibold">Student Reg ID</label>
                            <input type="text" name="student_reg_id" class="form-control" value="{{ $requests['student_reg_id'] ?? null}}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end justify-content-end gap-2">
                            <button class="btn btn-primary px-3 py-2" formaction="{{ route('loan.applications')}}" type="submit">
                                <i class="bx bx-search me-1"></i> Search
                            </button>
                            @if (Auth::user()->hasRole(['Admin','Super Admin']))
                            <button class="btn btn-success px-3 py-2" formaction="{{ route('genderate.loan.application.report')}}">
                                <i class="bx bx-file me-1"></i> Generate
                            </button>
                            @endif
                        </div>
                    </div>
                </form>
                
                <hr class="my-4"/>
                
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Loan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                       <tbody>
                        @foreach ($loans as $loan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ date('d M Y', strtotime($loan->created_at)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $loan->customer->first_name.' '.$loan->customer->last_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span>{{ $loan->customer->email }}</span>
                                        <span class="text-gray-500">{{ $loan->customer->phone_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($loan->amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ number_format($loan->loan_amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ number_format($loan->installment_amount) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $loan->plan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span>{!! $loan->loan_type_format !!}</span>
                                        <span class="text-gray-500">{{ $loan->get_device?->name }}</span>
                                        <span class="text-xs font-semibold">ID: {{ number_format($loan->initial_deposit) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{!! $loan->level_formatted !!}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('loan.profile',$loan->uuid)}}" class="text-decoration-none">
                                        <button class="btn btn-primary btn-sm shadow-sm" title="View Profile">
                                            <i class="bx bx-user"></i>
                                        </button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('filter-btn');
    const submitForm = document.getElementById('submit-form');
    
    if (filterBtn && submitForm) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (submitForm.style.display === 'none') {
                submitForm.style.display = 'block';
            } else {
                submitForm.style.display = 'none';
            }
        });
    }
});
</script>
@endsection

