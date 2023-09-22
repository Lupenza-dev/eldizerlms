@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
           <div class="col">
             <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Applications</p>
                            <h4 class="my-1 text-info">{{ number_format($loan_applications->count())}}</h4>
                            {{-- <p class="mb-0 font-13">+2.5% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bx-intersect'></i>
                        </div>
                    </div>
                </div>
             </div>
           </div>
           <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
               <div class="card-body">
                   <div class="d-flex align-items-center">
                       <div>
                           <p class="mb-0 text-secondary">Approved Application</p>
                           <h4 class="my-1 text-secondary">{{ number_format($loan_applications->where('level','GRANTED')->count())}}</h4>
                           {{-- <p class="mb-0 font-13">+5.4% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bx-check-circle'></i>
                       </div>
                   </div>
               </div>
            </div>
          </div>
          <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
               <div class="card-body">
                   <div class="d-flex align-items-center">
                       <div>
                           <p class="mb-0 text-danger">Rejected Application</p>
                           <h4 class="my-1 text-danger">{{ number_format($loan_applications->where('level','REJECTED')->count())}}</h4>
                           {{-- <p class="mb-0 font-13">-4.5% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bx-error-alt' ></i>
                       </div>
                   </div>
               </div>
            </div>
          </div>
          <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
               <div class="card-body">
                   <div class="d-flex align-items-center">
                       <div>
                           <p class="mb-0 text-secondary">Total Disbursment</p>
                           <h4 class="my-1 text-warning">{{ number_format($loan_contracts->sum('amount'))}}</h4>
                           {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bx-money'></i>
                       </div>
                   </div>
               </div>
            </div>
          </div> 
        </div><!--end row-->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
           <div class="col">
             <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Portofolio Size</p>
                            <h4 class="my-1 text-info">{{ number_format($loan_contracts->sum('loan_amount'))}}</h4>
                            {{-- <p class="mb-0 font-13">+2.5% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bx-money'></i>
                        </div>
                    </div>
                </div>
             </div>
           </div>
           <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
               <div class="card-body">
                   <div class="d-flex align-items-center">
                       <div>
                           <p class="mb-0 text-secondary">Granted Loans ({{ $loan_contracts->where('status','GRANTED')->count()}})</p>
                           <h4 class="my-1 text-info">{{ number_format($loan_contracts->where('status','GRANTED')->sum('loan_amount'))}}</h4>
                           {{-- <p class="mb-0 font-13">+5.4% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bx-list-ol'></i>
                       </div>
                   </div>
               </div>
            </div>
          </div>
          <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
               <div class="card-body">
                   <div class="d-flex align-items-center">
                       <div>
                           <p class="mb-0 text-secondary">Closed Loans ({{ $loan_contracts->where('status','CLOSED')->count()}})</p>
                           <h4 class="my-1 text-success">{{ number_format($loan_contracts->where('status','CLOSED')->sum('loan_amount'))}}</h4>
                           {{-- <p class="mb-0 font-13">-4.5% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bx-list-ol' ></i>
                       </div>
                   </div>
               </div>
            </div>
          </div>
          <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
               <div class="card-body">
                   <div class="d-flex align-items-center">
                       <div>
                           <p class="mb-0 text-secondary">Collected Amount</p>
                           <h4 class="my-1 text-warning">{{ number_format($loan_contracts->sum('current_balance'))}}</h4>
                           {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bx-money'></i>
                       </div>
                   </div>
               </div>
            </div>
          </div> 
        </div><!--end row-->

        <div class="row">
           <div class="col-12 col-lg-12 d-flex">
              <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Loan Application Report 2021</h6>
                        </div>
                        <div class="dropdown ms-auto">
                            {{-- <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                </li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
                  <div class="card-body">
                    <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Approved Applications</span>
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #F32755"></i>Rejected Applications</span>
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #16CA20"></i>Granted Loans</span>
                    </div>
                    <div class="chart-container-1">
                        <canvas id="chart1"></canvas>
                      </div>
                  </div>
                  {{-- <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                    <div class="col">
                      <div class="p-3">
                        <h5 class="mb-0">24.15M</h5>
                        <small class="mb-0">Overall Visitor <span> <i class="bx bx-up-arrow-alt align-middle"></i> 2.43%</span></small>
                      </div>
                    </div>
                    <div class="col">
                      <div class="p-3">
                        <h5 class="mb-0">12:38</h5>
                        <small class="mb-0">Visitor Duration <span> <i class="bx bx-up-arrow-alt align-middle"></i> 12.65%</span></small>
                      </div>
                    </div>
                    <div class="col">
                      <div class="p-3">
                        <h5 class="mb-0">639.82</h5>
                        <small class="mb-0">Pages/Visit <span> <i class="bx bx-up-arrow-alt align-middle"></i> 5.62%</span></small>
                      </div>
                    </div>
                  </div> --}}
              </div>
           </div>
           <div class="col-12 col-lg-6 d-flex">
               <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Universtity Loan Distribution</h6>
                        </div>
                        {{-- <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
                   <div class="card-body">
                    <div id="chart8"></div>
                   </div>
               </div>
           </div>
           <div class="col-12 col-lg-6 d-flex">
               <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Loan Status Distribution</h6>
                        </div>
                        {{-- <div class="dropdown ms-auto">
                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a>
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
                   <div class="card-body">
                    <div id="chart81"></div>
                   </div>
               </div>
           </div>
        </div><!--end row-->
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