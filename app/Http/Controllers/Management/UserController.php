<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Models\Management\Agent;
use Hash;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users =User::latest()->get();
        return view('managements.users.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $valid_data =$request->validated();

        $user =User::create([
            'name'  =>ucwords($valid_data['name']),
            'email' =>$valid_data['email'],
            'phone_number' =>$valid_data['phone_number'],
            'password'     =>Hash::make(123456),
            'uuid'         =>(string)Str::orderedUuid(),
        ]);

        $user->assignRole('Admin');

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);


    }

    public function userStatus(Request $request){
        $uuid   =$request->uuid;
        $action =$request->action;
        $status =($action == "activate") ? 1 : 2;

        $user =User::where('uuid',$uuid)->first();
        $user->active    =$status;
        $user->save();

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function userUpdate(Request $request)
    {
        $valid_data =$this->validate($request,[
            'name'           =>['required','min:3','max:50'],
            'id'             =>['required'],
            'phone_number'   =>['required','min:12','max:12'],
        ]);

        $user =User::where('uuid',$valid_data['id'])->update([
            'name' =>ucwords($valid_data['name']),
            'phone_number' =>$valid_data['phone_number']
        ]);

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request  $request)
    {
        $uuid   =$request->uuid;

        $user   =User::where('uuid',$uuid)->first();
        $agent  =Agent::where('user_id',$user->id)->first();
        if ($agent) {
            $agent->delete();
        }
        $user->delete();

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }
}
