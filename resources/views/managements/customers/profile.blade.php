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
    .profile-top{
        display: flex;flex;
        flex-direction: row;
        justify-content: space-between;
        align-content: center
    }
   .table th{
        background-color: aliceblue;
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
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
                <div class="text-center">
                    <h6 class="mb-0 text-uppercase">Customer Profile</h6>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Customer Name</th>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td colspan="2" rowspan="4" class="text-center">
                                        <img style="height: 170px; width: 150px; border-radius: 50%; " src="{{ asset('storage').'/'.$customer->image}}" alt="">   
                                    </td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $customer->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Nida Number</th>
                                    <td>{{ $customer->id_number }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ $customer->gender?->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Dob</th>
                                    <td>{{ $customer->dob }}</td>
                                </tr>
                                <tr>
                                    <th>Maritial Status</th>
                                    <td>{{ $customer->marital_status?->name }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $customer->address_location}}</td>
                                </tr>
                                <tr>
                                    <th>Residence Since</th>
                                    <td>{{ $customer->resident_since }}</td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="2"><b>College Details</b></td>
                                </tr>
                                <tr>
                                    <th>College Name</th>
                                    <td>{{ $customer->student?->college?->name }}</td>
                                </tr>
                                <tr>
                                    <th>Form Four Index No</th>
                                    <td>{{ $customer->student?->form_four_index_no }}</td>
                                </tr>
                                <tr>
                                    <th>Student Reg ID</th>
                                    <td>{{ $customer->student?->student_reg_id }}</td>
                                </tr>
                                <tr>
                                    <th>Study Year</th>
                                    <td>{{ $customer->student?->study_year }} </td>
                                </tr>
                                <tr>
                                    <th>Course</th>
                                    <td>{{ $customer->student?->course}}</td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td>{{ $customer->student?->position}}</td>
                                </tr>
                                <tr>
                                    <th>Heslb Status</th>
                                    <td>{!! $customer->student?->heslb_status_formatted !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

