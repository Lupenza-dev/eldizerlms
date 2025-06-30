<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentStoreRequest;
use App\Models\Management\Assignment;
use App\Models\Management\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Str;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges =College::get();
        $assignments =Assignment::get();
        return view('managements.assignments.index',compact('colleges','assignments'));
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
    public function store(AssignmentStoreRequest $request)
    {
        $valid_data =$request->validated();

        $assignment =Assignment::create([
            'name'            =>ucwords($valid_data['name']),
            'total_questions' =>$valid_data['total_questions'],
            'start_time' =>$valid_data['start_date'],
            'end_time'   =>$valid_data['end_date'],
            'college_id' =>$valid_data['college_id'],
            'uuid'       =>(string)Str::orderedUuid(),
            'user_id' =>Auth::user()->id
        ]);

        if ($valid_data['logo']) {
            $assignment->addMedia($valid_data['logo'])->toMediaCollection('images');
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
