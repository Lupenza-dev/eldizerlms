@extends('layouts.master')
@section('content')
<div class="page-wrapper bg-gray-50">
    <div class="page-content p-4">
        <!-- Filter Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <form id="filter_range" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bx bx-calendar me-2"></i>Start Date
                        </label>
                        <input type="date" id="start_date" name="start_date" value="{{ $start_date }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bx bx-calendar me-2"></i>End Date
                        </label>
                        <input type="date" id="end_date" name="end_date" value="{{ $end_date }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit" class="searchbtn bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors shadow-sm hover:shadow-md">
                            <i class="bx bx-search me-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- First Row of Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <!-- Customers Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Customers</p>
                            <h4 class="text-2xl font-bold text-blue-600" id="customers">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-user text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- App Downloads Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-indigo-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">App Downloads</p>
                            <h4 class="text-2xl font-bold text-indigo-600" id="app_users">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-download text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colleges Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Colleges</p>
                            <h4 class="text-2xl font-bold text-red-600" id="colleges">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-building text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agents Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Agents</p>
                            <h4 class="text-2xl font-bold text-amber-600" id="agents">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-user text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->hasRole(['Admin','Super Admin']))
        <!-- Second Row of Stats Cards (Admin Only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <!-- Approved Loan Applications Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Approved Loan Application</p>
                            <h4 class="text-2xl font-bold text-blue-600" id="approved_loan_applications">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-money text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected Loan Applications Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Rejected Loan Application</p>
                            <h4 class="text-2xl font-bold text-blue-600" id="rejected_applications">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-list-ol text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portfolio Size Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Portfolio Size</p>
                            <h4 class="text-2xl font-bold text-green-600" id="total_contract_sum">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-list-ol text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disbursed Amount Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Disbursed Amount</p>
                            <h4 class="text-2xl font-bold text-amber-600" id="disbursed_amount">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-money text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Row of Stats Cards (Admin Only) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <!-- Collected Amount Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Collected Amount</p>
                            <h4 class="text-2xl font-bold text-blue-600" id="total_collected">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-money text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Outstanding Amount Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Outstanding Amount</p>
                            <h4 class="text-2xl font-bold text-blue-600" id="outstanding_amount">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-list-ol text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expected Interest Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Expected Interest</p>
                            <h4 class="text-2xl font-bold text-green-600" id="expected_interest">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-list-ol text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collected Interest Card -->
            <div class="bg-white rounded-xl shadow-sm border-l-4 border-amber-500 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Collected Interest</p>
                            <h4 class="text-2xl font-bold text-amber-600" id="collected_interest">0</h4>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white shadow-lg">
                            <i class='bx bx-money text-lg'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Loan Application Report Chart -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h6 class="text-lg font-semibold text-gray-800">
                            Loan Application Report 
                            <span class="text-sm text-blue-600">(<span class="start_date">{{ $start_date}}</span> to <span class="end_date">{{ $end_date}}</span>)</span>
                        </h6>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 cursor-pointer hover:bg-blue-200 transition-colors">
                                <i class="bx bxs-circle me-1 text-blue-600"></i>Approved Applications
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 cursor-pointer hover:bg-red-200 transition-colors">
                                <i class="bx bxs-circle me-1 text-red-600"></i>Rejected Applications
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="chart1"></canvas>
                    </div>
                </div>
            </div>

            <!-- Payments Report Chart -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h6 class="text-lg font-semibold text-gray-800">
                            Payments Reports 
                            <span class="text-sm text-blue-600">(<span class="start_date">{{ $start_date}}</span> to <span class="end_date">{{ $end_date}}</span>)</span>
                        </h6>
                        <div class="flex gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 cursor-pointer hover:bg-green-200 transition-colors">
                                <i class="bx bxs-circle me-1 text-green-600"></i>Granted Loans
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 cursor-pointer hover:bg-emerald-200 transition-colors">
                                <i class="bx bxs-circle me-1 text-emerald-600"></i>Payment Collected
                            </span>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="chart12"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>

<script>
$(document).ready(function(){
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    cardData(start_date, end_date);
    
    var promise = ajaxCall(start_date, end_date).done(function(response) {
        console.log(response);
        barChartApplication(response.approved_applications, response.rejected_applications); 
        barChartLoan(response.granted_loans, response.payment_collections); 
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
    });
});

function ajaxCall(start_date, end_date) { 
    return $.ajax({
        type: 'POST',
        url: "{{ route('admin.bar.charts') }}",
        data: {start_date: start_date, end_date: end_date},
        success: function(response){
            // Handle response inside success function if needed
        },
        error: function(response){
            console.log(response.responseText);
        }
    }); 
}

function barChartApplication(approved_applications, rejected_applications) {
    "use strict";
    var ctx = document.getElementById("chart1").getContext('2d');
   
    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, '#6078ea');  
    gradientStroke1.addColorStop(1, '#17c5ea'); 
   
    var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, '#16CA20');
    gradientStroke2.addColorStop(1, '#16CA20');

    var gradientStroke3 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke3.addColorStop(0, '#F32755');
    gradientStroke3.addColorStop(1, '#F32755');

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Approved Application',
                data: JSON.parse(approved_applications),
                borderColor: gradientStroke1,
                backgroundColor: gradientStroke1,
                hoverBackgroundColor: gradientStroke1,
                pointRadius: 0,
                fill: false,
                borderRadius: 20,
                borderWidth: 0
            }, {
                label: 'Rejected Applications',
                data: JSON.parse(rejected_applications),
                borderColor: gradientStroke3,
                backgroundColor: gradientStroke3,
                hoverBackgroundColor: gradientStroke3,
                pointRadius: 0,
                fill: false,
                borderRadius: 20,
                borderWidth: 0 
            }]
        },
        options: {
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            plugins: {
                legend: {
                    display: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }); 
}

$('#filter_range').on('submit', function(e){
    e.preventDefault();
    $('.searchbtn').html('<i class="bx bx-loader bx-spin me-2"></i>Loading...');
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    $('.start_date').html(start_date);
    $('.end_date').html(end_date);
    cardData(start_date, end_date);
    var promise = ajaxCall(start_date, end_date).done(function(response) {
        console.log(response);
        barChartApplication(response.approved_applications, response.rejected_applications); 
        barChartLoan(response.granted_loans, response.payment_collections); 
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
    });
    $('.searchbtn').html('<i class="bx bx-search me-2"></i>Filter');
});

function cardData(start_date, end_date){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: "{{ route('dashboard.data')}}",
        data: {start_date: start_date, end_date: end_date},
        success: function(response){
            $('#customers').html(response.customers);
            $('#app_users').html(response.app_users);
            $('#colleges').html(response.colleges);
            $('#agents').html(response.agents);
            $('#approved_loan_applications').html(response.approved_loan_applications);
            $('#rejected_applications').html(response.rejected_applications);
            $('#total_contract_sum').html(response.total_contract_sum);
            $('#disbursed_amount').html(response.disbursed_amount);
            $('#total_collected').html(response.total_collected);
            $('#outstanding_amount').html(response.outstanding_amount);
            $('#expected_interest').html(response.expected_interest);
            $('#collected_interest').html(response.collected_interest);
        },
        error: function(response){
            console.log(response.responseText);
        }
    });
}

function barChartLoan(granted_loans, payment_collections) {
    "use strict";
    var ctx = document.getElementById("chart12").getContext('2d');
   
    var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke1.addColorStop(0, '#6078ea');  
    gradientStroke1.addColorStop(1, '#17c5ea'); 
   
    var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke2.addColorStop(0, '#16CA20');
    gradientStroke2.addColorStop(1, '#16CA20');
      
    var myChart2 = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Granted Loans',
                data: JSON.parse(granted_loans),
                borderColor: gradientStroke1,
                backgroundColor: gradientStroke1,
                hoverBackgroundColor: gradientStroke1,
                pointRadius: 0,
                fill: false,
                borderRadius: 20,
                borderWidth: 0
            }, {
                label: 'Payment Collected',
                data: JSON.parse(payment_collections),
                borderColor: gradientStroke2,
                backgroundColor: gradientStroke2,
                hoverBackgroundColor: gradientStroke2,
                pointRadius: 0,
                fill: false,
                borderRadius: 20,
                borderWidth: 0 
            }]
        },
        options: {
            maintainAspectRatio: false,
            barPercentage: 0.5,
            categoryPercentage: 0.8,
            plugins: {
                legend: {
                    display: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
</script>
@endpush