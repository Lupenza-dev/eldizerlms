<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssignmentQuestionRequest;
use App\Http\Requests\UpdateAssignmentQuestionRequest;
use App\Models\Management\Assignment;
use App\Models\Management\AssignmentQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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
        $questions =AssignmentQuestion::where('assignment_id',$assignment->id)->get();
        return view('managements.assignments.question_list',compact('assignment','questions'));
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
    public function store(Request $request)
    {
        foreach ($request->questions as $questionData) {
            AssignmentQuestion::create([
                'name' => $questionData['name'],
                'choices' => $questionData['choices'],
                'correct_answer' => $questionData['correct_answer'],
                'assignment_id' => $request->assignment_id,
                'user_id' => Auth::id(),
                'uuid' => (string) Str::orderedUuid(),
            ]);
        }

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
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
