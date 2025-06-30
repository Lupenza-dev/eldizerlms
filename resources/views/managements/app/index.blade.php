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
            <div class="breadcrumb-title pe-3">App Management</div>
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
                <h6 class="mb-0 text-uppercase text-center">App Management</h6>
                <hr/>
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('assignments.index')}}">
                            <div class="card shadow border p-3">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <i class="bx bx-phone fs-1 text-primary"></i> 
                                  </div>
                                  <div class="col">
                                    <h5 class="mb-1">Assignments Management</h5>
                                    <p class="mb-0 text-muted">Management of Assignment per University</p>
                                  </div>
                                </div>
                            </div>
                        </a>
                      </div>
                    <div class="col-md-6">
                        <a href="{{ route('groups.index')}}">
                            <div class="card shadow border p-3">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <i class="bx bx-group fs-1 text-primary"></i> 
                                  </div>
                                  <div class="col">
                                    <h5 class="mb-1">Group Management</h5>
                                    <p class="mb-0 text-muted">Management  University Groups</p>
                                  </div>
                                </div>
                            </div>
                        </a>
                      </div>
                    <div class="col-md-6">
                        <a href="{{ route('adverts.index')}}">
                            <div class="card shadow border p-3">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <i class="bx bx-cloud-download fs-1 text-primary"></i> 
                                  </div>
                                  <div class="col">
                                    <h5 class="mb-1">Adverts Management</h5>
                                    <p class="mb-0 text-muted">Advert Management  create,update,delete</p>
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

