@extends('layouts.master')
@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="row mb-5">
                <form id="filter_range">
                    <div class="form-group row">
                        <div class="col-md-4"></div>
                        <div class="col-md-3">
                            <label for="">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date }}" required> 
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info btn-sm mt-4 searchbtn"><i class="bx bx-search"></i> Filter</button>
                        </div>
                    </div>
                   
                </form>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
           <div class="col">
             <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Customers</p>
                            <h4 class="my-1 text-info" id="customers">0</h4>
                            {{-- <p class="mb-0 font-13">+2.5% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bx-user'></i>
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
                           <p class="mb-0 text-secondary">App Downloads</p>
                           <h4 class="my-1 text-secondary" id="app_users">0</h4>
                           {{-- <p class="mb-0 font-13">+5.4% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class='bx bx-download'></i>
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
                           <p class="mb-0 text-danger">Colleges</p>
                           <h4 class="my-1 text-danger" id="colleges">0</h4>
                           {{-- <p class="mb-0 font-13">-4.5% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class='bx bx-building' ></i>
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
                           <p class="mb-0 text-secondary">Agent</p>
                           <h4 class="my-1 text-warning" id="agents">0</h4>
                           {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                       </div>
                       <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bx-user'></i>
                       </div>
                   </div>
               </div>
            </div>
          </div> 
        </div><!--end row-->
        @if (Auth::user()->hasRole(['Admin','Super Admin']))
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
              <div class="card radius-10 border-start border-0 border-4 border-info">
                 <div class="card-body">
                     <div class="d-flex align-items-center">
                         <div>
                             <p class="mb-0 text-secondary">Approved Loan Application</p>
                             <h4 class="my-1 text-info" id="approved_loan_applications">0</h4>
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
                            <p class="mb-0 text-secondary">Rejected Loan Application</p>
                            <h4 class="my-1 text-info" id="rejected_applications">0</h4>
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
                            <p class="mb-0 text-secondary">Portofolio Size</p>
                            <h4 class="my-1 text-success" id="total_contract_sum">0</h4>
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
                            <p class="mb-0 text-secondary">Disbursed Amount</p>
                            <h4 class="my-1 text-warning" id="disburesed_amount">0</h4>
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
                             <p class="mb-0 text-secondary">Collected Amount</p>
                             <h4 class="my-1 text-info" id="total_collected">0</h4>
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
                            <p class="mb-0 text-secondary">Outstanding Amount</p>
                            <h4 class="my-1 text-info" id="outstanding_amount">0</h4>
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
                            <p class="mb-0 text-secondary">Expected Interest</p>
                            <h4 class="my-1 text-success" id="expected_interest">0</h4>
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
                            <p class="mb-0 text-secondary">Collected interest</p>
                            <h4 class="my-1 text-warning" id="collected_interest">0</h4>
                            {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i class='bx bx-money'></i>
                        </div>
                    </div>
                </div>
             </div>
           </div> 
        </div><!--end row--> 
        @endif
      

        <div class="row">
           <div class="col-6 col-lg-6 d-flex">
              <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Loan Application Report <span class="start_date">{{ $start_date}}</span> to <span class="end_date">{{ $end_date}}</span></h6>
                        </div>
                        <div class="dropdown ms-auto">
                        </div>
                    </div>
                </div>
                  <div class="card-body">
                    <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Approved Applications</span>
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #F32755"></i>Rejected Applications</span>
                    </div>
                    <div class="chart-container-1">
                        <canvas id="chart1"></canvas>
                      </div>
                  </div>
              </div>
           </div>
           <div class="col-6 col-lg-6 d-flex">
              <div class="card radius-10 w-100">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Payments Reports from <span class="start_date">{{ $start_date}}</span> to <span class="end_date">{{ $end_date}}</span></h6>
                        </div>
                        <div class="dropdown ms-auto">
                        </div>
                    </div>
                </div>
                  <div class="card-body">
                    <div class="d-flex align-items-center ms-auto font-13 gap-2 mb-3">
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Granted Loans</span>
                        <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #16CA20"></i>Payment Collected</span>
                    </div>
                    <div class="chart-container-1">
                        <canvas id="chart12"></canvas>
                      </div>
                  </div>
              </div>
           </div>
           @if (Auth::user()->hasRole(['Admin','Super Admin']))
           {{-- <div class="col-12 col-lg-6 d-flex">
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
        {{-- </div>
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
        {{-- </div>  --}}
           @endif
          
        </div><!--end row-->
    </div>
</div>    
@endsection
@push('scripts')
<script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
{{-- <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apex-custom.js')}}"></script> --}}

<script>
   
    $(document).ready(function(){
        var start_date =$('#start_date').val();
        var end_date   =$('#end_date').val();
        cardData(start_date,end_date);
        
        var promise=ajaxCall(start_date,end_date).done(function(response) {
            console.log(response);
            barChartApplication(response.approved_applications,response.rejected_applications); 
            barChartLoan(response.granted_loans,response.payment_collections); 
        }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error(errorThrown);
        });
    });


    function ajaxCall(start_date,end_date) { 
        return $.ajax({
            type:'POST',
            url:" {{ route('admin.bar.charts') }}",
           // contentType: false,
           // cache: false,
           // processData : false,
            data : {start_date:start_date,end_date:end_date},
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
function barChartApplication(approved_applications,rejected_applications) {
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

  var myChart = null;

      if (myChart) {
        myChart.destroy(); // Destroy the existing chart
      }

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
          }
           ]
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

    $('#filter_range').on('submit',function(e){
    e.preventDefault();
    $('#searchbtn').html('<i class="fa fa-spinner fa-pulse fa-spin"></i> Loading .........');
           var start_date =$('#start_date').val();
            var end_date   =$('#end_date').val();
            $('.start_date').html(start_date);
            $('.end_date').html(end_date);
           // console.log(start_date,end_date);
            cardData(start_date,end_date);
            // var promise=ajaxCall(start_date,end_date).done(function(response) {
            // console.log(response);
            // barChartApplication(response.approved_applications,response.rejected_applications); 
            // barChartLoan(response.granted_loans,response.payment_collections); 
            // }).fail(function(jqXHR, textStatus, errorThrown) {
            // console.error(errorThrown);
            // });
    $('#searchbtn').html('<i class="bx bx-search"></i> Search');
    })

function cardData(start_date,end_date){
    $.ajaxSetup({
      headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
          });
      $.ajax({
      type:'POST',
      url:"{{ route('dashboard.data')}}",
      data : {start_date:start_date,end_date:end_date},
     // contentType: false,
     // cache: false,
      //processData : false,
      success:function(response){
        $('#customers').html(response.customers);
        $('#app_users').html(response.app_users);
        $('#colleges').html(response.colleges);
        $('#agents').html(response.agents);
        $('#approved_loan_applications').html(response.approved_loan_applications);
        $('#rejected_applications').html(response.rejected_applications);
        $('#total_contract_sum').html(response.total_contract_sum);
        $('#disburesed_amount').html(response.disburesed_amount);
        $('#total_collected').html(response.total_collected);
        $('#outstanding_amount').html(response.outstanding_amount);
        $('#expected_interest').html(response.expected_interest);
        $('#collected_interest').html(response.collected_interest);
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
}

function barChartLoan(granted_loans,payment_collections) {
    "use strict";
// chart 1

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
          },{
            label: 'Payment Collected',
            data: JSON.parse(payment_collections),
            borderColor: gradientStroke2,
            backgroundColor: gradientStroke2,
            hoverBackgroundColor: gradientStroke2,
            pointRadius: 0,
            fill: false,
            borderRadius: 20,
            borderWidth: 0 
          }
           ]
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

<script>
    
</script>
    
@endpush