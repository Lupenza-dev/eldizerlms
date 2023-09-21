@extends('layouts.app')
@section('content')
<div class="wrapper">
    <div class="section-authentication-cover">
        <div class="">
            <div class="row g-0">

                <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                    <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                        <div class="card-body">
                             <img src="assets/images/login-images/login-cover.svg" class="img-fluid auth-img-cover-login" width="650" alt=""/>
                             
                        </div>
                    </div>
                    
                </div>

                <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                    <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                        <div class="card-body p-sm-5">
                            <div class="">
                                <div class="mb-3 text-center">
                                    {{-- <img src="assets/images/logo-icon.png" width="60" alt=""> --}}
                                    <img style="width: 200px" src="{{ asset('assets/images/eldizerlogo.jpeg')}}" class="logo-icon" alt="logo icon">
                                </div>
                                <div class="text-center mb-4">
                                    {{-- <h5 class="">Rocker Admin</h5> --}}
                                    <p class="mb-0">Please log in to your account</p>
                                </div>
                                <div class="form-body">
                                    <form class="row g-3" id="user_auth">
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Email</label>
                                            <input type="email" name="username" class="form-control" id="inputEmailAddress" placeholder="jhon@example.com">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" name="password" class="form-control border-end-0" id="inputChoosePassword" value="12345678" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="remember_me" type="checkbox" id="flexSwitchCheckChecked">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">	<a href="authentication-forgot-password.html">Forgot Password ?</a>
                                        </div>
                                        <div class="col-12" id="user_auth_alert">

                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" id="user_btn" class="btn btn-primary"> <span class="bx bx-lock"></span> Sign in</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center ">
                                                <p class="mb-0">Don't have an account yet? <a href="#">Sign up here</a>
                                                </p>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                {{-- <div class="login-separater text-center mb-5"> <span>OR SIGN IN WITH</span>
                                    <hr>
                                </div>
                                <div class="list-inline contacts-social text-center">
                                    <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
                                    <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
                                    <a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
                                    <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end row-->
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#user_auth').on('submit',function(e){
            e.preventDefault();
         var dataz =$(this).serialize();
            $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
            });
  
        $.ajax({
        type:'POST',
        url:"{{ route('authentication')}}",
        data:dataz,
        success:function(response){
            console.log(response);
            $.notify(response.message, "success");
          setTimeout(function(){
            window.location.href=response.url;
          },500);
         
        },
        error:function(response){
            console.log(response.responseText);
            if (jQuery.type(response.responseJSON.errors) == "object") {
              $('#user_auth_alert').html('');
            $.each(response.responseJSON.errors,function(key,value){
                $('#user_auth_alert').append('<div class="alert alert-danger">'+value+'</div>');
            });
            } else {
               $('#user_auth_alert').html('<div class="alert alert-danger">'+response.responseJSON.errors+'</div>');
            }
        },
        beforeSend : function(){
            $('#user_btn').html('<span class="fas fa-spinner fas-pulse fas-spin"></span> Authenticating ---');
            $('#user_btn').attr('disabled', true);
        },
       complete : function(){
            $('#user_btn').html('<span class="fas fa-sign-in-alt"></span> Sign In');
            $('#user_btn').attr('disabled', false);
        }
        });
    });
    });
  </script>
    
@endpush

