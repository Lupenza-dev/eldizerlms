<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use URL;
use App\Models\User;
use Carbon\Carbon;
use Redirect;
use Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

   // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function authentication(Request $request)
    {
        $this->validate(
            $request,
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'username is required',
                'password.required' => 'Password is required',
            ]
        );

        $username =$request->input('username');
        $password =$request->input('password');
        $remember =$request->input('remember');
        if (Auth::attempt(['email' => $username, 'password' => $password],$remember)) {
            $user = User::find(auth()->user()->id);
            if ($user->active == 1) { 

               if ($user->hasRole('Admin') || $user->hasRole('Super Admin')) {
                return response()->json([
                    'success' =>true,
                    'message' =>greeting().' '.$user->name.' Welcome Again at ELDizer Finance LMS',
                    'url'     =>$user->is_password_changed ? URL::to('admin/dashboard') : URL::to('change/password')
                ]);
               }else if($user->hasRole('Agent')){
                return response()->json([
                    'success' =>true,
                    'message' =>greeting().' '.$user->name.' Welcome Again at ELDizer Finance LMS',
                    'url'     =>$user->is_password_changed ? URL::to('dashboard') : URL::to('change/password')
                ]);
               }
                else {
                Auth::logout();
                return response()->json([
                    'success' =>false,
                    'errors' =>'You dont have Permission to access this site',
                    ''
                ],500);
               }
               

            } else {
                Auth::logout();
                return response()->json([
                    'success' =>false,
                    'errors' =>'Your Account has been Deactivated , Contact System Adminstrator to Activate Your Account',
                ],500);
            }
        } else {
            return response()->json([
                'success' =>false,
                'errors' =>'Invalid email/Username or Password',
            ],500);
        }
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::route('/');
    }

    public function changePassword(){
        return view('auth.change_password');
    }

    public function passwordChange(Request $request){
        $valid =$this->validate($request,[
            'old_password' =>'required',
            'password'     =>['required','confirmed','string','min:6','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/','regex:/[@$!%*#?&]/',
            ],
        ]);

        $user =Auth::user();
        if (!Hash::check($valid['old_password'],$user->password)) {
            return response()->json([
                'success' =>false,
                'errors'  =>"Old Password is not correct",
            ],500);
        }

        $user->password =Hash::make($valid['password']);
        $user->is_password_changed =true;
        $user->password_change_date =Carbon::now();
        $user->save();
        
        return response()->json([
            'success' =>true,
            'message' =>greeting().' '.$user->name.' Welcome Again at ELDizer Finance LMS',
            'url'     =>URL::to('dashboard')
        ]);


    }
}
