@extends('layouts.master')
@section('content')
<div class="page-wrapper" style="background-color:#f1f5f9;">
    <div class="page-content">

        {{-- Row 1: Always-visible stat cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
            {{-- Total Applications --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-intersect text-cyan-600 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Total Applications</p>
                    <h4 class="text-2xl font-bold text-cyan-600 mb-0">{{ number_format($loan_applications->count()) }}</h4>
                </div>
            </div>
            {{-- Approved Applications --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-check-circle text-blue-600 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Approved Applications</p>
                    <h4 class="text-2xl font-bold text-blue-600 mb-0">{{ number_format($loan_applications->where('level','GRANTED')->count()) }}</h4>
                </div>
            </div>
            {{-- Rejected Applications --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-error-alt text-red-500 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Rejected Applications</p>
                    <h4 class="text-2xl font-bold text-red-500 mb-0">{{ number_format($loan_applications->whereIn('level',['Rejected by Agent','Rejected by Admin'])->count()) }}</h4>
                </div>
            </div>
            {{-- Total Disbursement --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-money text-amber-500 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Total Disbursement</p>
                    <h4 class="text-2xl font-bold text-amber-500 mb-0">{{ number_format($loan_contracts->sum('amount')) }}</h4>
                </div>
            </div>
        </div>

        {{-- Row 2: Admin-only stat cards --}}
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
            {{-- Portfolio Size --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-money text-cyan-600 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Portfolio Size</p>
                    <h4 class="text-2xl font-bold text-cyan-600 mb-0">{{ number_format($loan_contracts->sum('loan_amount')) }}</h4>
                </div>
            </div>
            {{-- Granted Loans --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-list-ol text-blue-600 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Granted Loans <span class="text-blue-400">({{ $loan_contracts->where('status','GRANTED')->count() }})</span></p>
                    <h4 class="text-2xl font-bold text-blue-600 mb-0">{{ number_format($loan_contracts->where('status','GRANTED')->sum('loan_amount')) }}</h4>
                </div>
            </div>
            {{-- Closed Loans --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-list-check text-emerald-600 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Closed Loans <span class="text-emerald-400">({{ $loan_contracts->where('status','CLOSED')->count() }})</span></p>
                    <h4 class="text-2xl font-bold text-emerald-600 mb-0">{{ number_format($loan_contracts->where('status','CLOSED')->sum('loan_amount')) }}</h4>
                </div>
            </div>
            {{-- Collected Amount --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bx bx-wallet text-amber-500 text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Collected Amount</p>
                    <h4 class="text-2xl font-bold text-amber-500 mb-0">{{ number_format($loan_contracts->sum('current_balance')) }}</h4>
                </div>
            </div>
        </div>
        @endif

        {{-- Bar Chart --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-4">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h6 class="text-sm font-semibold text-slate-700 mb-0">Loan Application Report</h6>
                <div class="flex items-center gap-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1"><i class="bx bxs-circle text-cyan-400"></i> Approved</span>
                    <span class="flex items-center gap-1"><i class="bx bxs-circle text-red-400"></i> Rejected</span>
                    <span class="flex items-center gap-1"><i class="bx bxs-circle text-emerald-400"></i> Granted</span>
                </div>
            </div>
            <div class="p-6">
                <div class="chart-container-1">
                    <canvas id="chart1"></canvas>
                </div>
            </div>
        </div>

        {{-- Pie Charts (Admin only) --}}
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h6 class="text-sm font-semibold text-slate-700 mb-0">University Loan Distribution</h6>
                </div>
                <div class="p-6">
                    <div id="chart8"></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h6 class="text-sm font-semibold text-slate-700 mb-0">Loan Status Distribution</h6>
                </div>
                <div class="p-6">
                    <div id="chart81"></div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
{{-- <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apex-custom.js')}}"></script> --}}

<script>
    $(document).ready(function(){
        unipiechart(); 
        unipiechart2(); 
        
        var promise=ajaxCall().done(function(response) {
            console.log(response);
            barChart(response.approved_applications,response.rejected_applications,response.granted_loans); 
        }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
        });
    });


    function ajaxCall() { 
        return $.ajax({
            type:'GET',
            url:" {{ route('admin.bar.chart') }}",
            contentType: false,
            cache: false,
            processData : false,
            success:function(response){
                // Handle the response inside the success function if needed
            },
            error:function(response){
                console.log(response.responseText);
            },
            beforeSend : function(){
               
            },
            complete : function(){
              }
        }); 
    }
</script>
<script>
    function barChart(approved_applications,rejected_applications,granted_loans) {
    "use strict";
// chart 1

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
          },{
            label: 'Rejected Applications',
            data: JSON.parse(rejected_applications),
            borderColor: gradientStroke3,
            backgroundColor: gradientStroke3,
            hoverBackgroundColor: gradientStroke3,
            pointRadius: 0,
            fill: false,
            borderRadius: 20,
            borderWidth: 0 
          },
           {
            label: 'Disbursed Loans',
            data: JSON.parse(granted_loans),
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
   };	
   
   function unipiechart(){
    var st_john     =@json($loan_contracts->where('college_id',5)->count());
    var cbe         =@json($loan_contracts->where('college_id',6)->count());
    var udom        =@json($loan_contracts->where('college_id',7)->count());
    var options = {
		series: [st_john, cbe, udom],
		//series: [518, 10, 10],
		chart: {
			foreColor: '#9ba7b2',
			height: 330,
			type: 'pie',
		},
		colors: ["#0d6efd", "#6c757d", "#17a00e"],
		labels: ['ST JOHN UNIVERSITY', 'CBE DODMA', 'UNIVERSITY OF DODOMA'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					height: 360
				},
				legend: {
					position: 'bottom'
				}
			}
		}]
	};
	var chart = new ApexCharts(document.querySelector("#chart8"), options);
	chart.render();
   }

   function unipiechart2(){
    var GRANTED        =@json($loan_contracts->where('status','GRANTED')->count());
    var CLOSED        =@json($loan_contracts->where('status','CLOSED')->count());
    var DEFAULT        =@json($loan_contracts->where('status','DEFAULT')->count());

    var options = {
		series: [GRANTED, CLOSED, DEFAULT],
		chart: {
			foreColor: '#9ba7b2',
			height: 330,
			type: 'pie',
		},
		colors: ["#0d6efd", "#6c757d", "#17a00e"],
		labels: ['GRANTED', 'CLOSED', 'DEFAULT'],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					height: 360
				},
				legend: {
					position: 'bottom'
				}
			}
		}]
	};
	var chart = new ApexCharts(document.querySelector("#chart81"), options);
	chart.render();
   }
   
</script>
    
@endpush