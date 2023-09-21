<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\Agent;
use App\Models\User;
use App\Models\Management\College;
use App\Http\Requests\AgentStoreRequest;
use Str;
use DB;
use Storage;
use Hash;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents =Agent::with('user','college')->latest()->get();
        $colleges =College::orderBy('name','ASC')->get();
        return view('managements.agents.list',compact('colleges','agents'));
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
    public function store(AgentStoreRequest $request)
    {
        $valid_data =$request->validated();

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filename =time().$file->getClientOriginalName();
            $path     =Storage::disk('local')->putFileAs('',$file,$filename);
           
        }

        try {
            DB::transaction(function() use ($valid_data,$path){
                $user =User::create([
                    'name'  =>ucwords($valid_data['name']),
                    'email' =>$valid_data['email'],
                    'phone_number' =>$valid_data['phone_number'],
                    'password'     =>Hash::make(123456),
                    'uuid'         =>(string)Str::orderedUuid(),
                ]);
        
                $user->assignRole('Agent');
        
                $agent =Agent::create([
                    'student_reg_id' =>$valid_data['student_reg_id'],
                    'user_id'        =>$user->id,
                    'college_id'     =>$valid_data['college_id'],
                    'image'          =>$path,
                    'uuid'           =>(string)Str::orderedUuid(),
                ]);
            });
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
