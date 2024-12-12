<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use App\Models\Management\College;
use App\Models\Management\HESLBBeneficary;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Facades\DataTables;
// use DataTables;

class BenefeciariesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges =College::get();
        return view('managements.student.list',compact('colleges'));
    }

    public function getBeneficariesData(){
        $data =HESLBBeneficary::select([
            'index_number','full_name','code','course_code','reg_no','year_of_study','academic_year'
        ]);
        return DataTables::of($data)
        ->make(true);
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
    public function store(Request $request)
    {
        $valid_data =  $this->validate(
            $request,
            [
                'college_id' => 'required',
                'file' => 'required',
            ]
        );

        Excel::import(new StudentImport($valid_data['college_id']),$request->file('file'));

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
