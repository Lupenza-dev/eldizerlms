<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentQuestionRequest;
use App\Http\Requests\UpdateAssignmentQuestionRequest;
use App\Models\Management\Assignment;
use App\Models\Management\AssignmentQuestion;

class AssignmentQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function questionList(Assignment $assignment){
        return view('managements.assignments.question_list',compact('assignment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Assignment $assignment)
    {
        return view('managements.assignments.question_create',compact('assignment'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentQuestionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AssignmentQuestion $assignmentQuestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssignmentQuestion $assignmentQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentQuestionRequest $request, AssignmentQuestion $assignmentQuestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssignmentQuestion $assignmentQuestion)
    {
        //
    }
}
