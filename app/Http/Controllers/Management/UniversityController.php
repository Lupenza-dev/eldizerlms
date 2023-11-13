<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CollegeStoreRequest;
use App\Models\Management\CollegeRepresentative;
use App\Models\Management\College;
use Storage;
use Str;
use Auth;
use Illuminate\Support\Facades\Log;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges =College::latest()->get();
        return view('managements.colleges.list',compact('colleges'));
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
    public function store(CollegeStoreRequest $request)
    {
        $valid_data =$request->validated();

        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            $filename =time().$file->getClientOriginalName();
            $path = Storage::disk('local')->putFileAs('',$file,$filename);
           
        }

        $college =College::create([
            'name'     =>ucwords($valid_data['name']),
            'location' =>ucwords($valid_data['location']),
            'uuid'     =>(string)Str::orderedUuid(),
            'logo'      =>$path,
            'created_by' =>Auth::user()->id
        ]);

        $college_rep =CollegeRepresentative::create([
            'name' =>ucwords($valid_data['rep_name']),
            'phone_number' =>$valid_data['rep_phone_number'],
            'position'     =>$valid_data['position'],
            'college_id'   =>$college->id,
            'uuid'         =>(string)Str::orderedUuid()
        ]);

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }

    public function collegeUpdate(Request $request){
        $valid_data =$this->validate($request,[
            'id'     =>['required'],
            'name'     =>['required'],
            'location' =>['required'],
            'rep_name' =>['required'],
            'rep_phone_number' =>['required'],
            'position'         =>['required'],
        ]);

        $college =College::where('uuid',$valid_data['id'])->first();
        $college->name    =ucwords($valid_data['name']);
        $college->location =ucwords($valid_data['location']);
        $college->save();

         $college_rep =CollegeRepresentative::where('college_id',$college->id)->updateOrCreate([
            'college_id' =>$college->id
         ],
        [
            'name'         =>ucwords($valid_data['rep_name']),
            'phone_number' =>$valid_data['rep_phone_number'],
            'position'     =>$valid_data['position'],
            'uuid'         =>(string)Str::orderedUuid()  
        ]);

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }

    public function collegeStatus(Request $request){
        $uuid   =$request->uuid;
        $action =$request->action;
        $status =($action == "activate") ? "active" : "Inactive";

        $college =College::where('uuid',$uuid)->first();
        $college->status    =$status;
        $college->save();

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
    public function destroy(Request  $request)
    {
        $uuid   =$request->uuid;

        $college =College::where('uuid',$uuid)->delete();

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }
}
