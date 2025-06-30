<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Management\College;
use App\Models\Management\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups =Group::latest()->get();
        $colleges =College::get();
        return view('managements.groups.index',compact('groups','colleges'));
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
    public function store(StoreGroupRequest $request)
    {
        $valid_data =$request->validated();

        $assignment =Group::create([
            'name'            =>ucwords($valid_data['name']),
            'link'            =>$valid_data['link'],
            'college_id'      =>$valid_data['college_id'],
            'uuid'            =>(string)Str::orderedUuid(),
            'user_id'         =>Auth::user()->id
        ]);

        if ($valid_data['image']) {
            $assignment->addMedia($valid_data['image'])->toMediaCollection('images');
         }

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
