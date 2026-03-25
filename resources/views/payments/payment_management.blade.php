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
            <div class="breadcrumb-title pe-3">Payment Management</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-building"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>
            {{-- <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleLargeModal"> <span class="bx bx-plus"></span> Add</button>
                </div>
            </div> --}}
        </div>
        <!--end breadcrumb-->
       
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-center mb-2">Payment Management</h6>
                <hr/>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('payments')}}">
                            <div class="card shadow border p-3">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <i class="bx bx-phone fs-1 text-primary"></i> 
                                  </div>
                                  <div class="col">
                                    <h5 class="mb-1">All Payments</h5>
                                    <p class="mb-0 text-muted">Management of Payments</p>
                                  </div>
                                </div>
                            </div>
                        </a>
                      </div>
                    <div class="col-md-6">
                        <a href="{{ route('payment.mandates')}}">
                            <div class="card shadow border p-3">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <i class="bx bx-group fs-1 text-primary"></i> 
                                  </div>
                                  <div class="col">
                                    <h5 class="mb-1">All Payment Mandates</h5>
                                    <p class="mb-0 text-muted">Management  Payment Mandates</p>
                                  </div>
                                </div>
                            </div>
                        </a>
                      </div>
                    <div class="col-md-6">
                        <a href="{{ route('customer.loans.mandates')}}">
                            <div class="card shadow border p-3">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <i class="bx bx-cloud-download fs-1 text-primary"></i> 
                                  </div>
                                  <div class="col">
                                    <h5 class="mb-1">Customer Loan Mandates</h5>
                                    <p class="mb-0 text-muted">Customer Applied Loans Mandates</p>
                                  </div>
                                </div>
                            </div>
                        </a>
                      </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

    
    
@endsection

