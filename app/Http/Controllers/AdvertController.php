<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdvertRequest;
use App\Http\Requests\UpdateAdvertRequest;
use App\Models\Management\Advert;
use App\Models\Management\College;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adverts   =Advert::latest()->get();
        $colleges =College::get();
        return view('managements.adverts.index',compact('adverts','colleges'));
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
    public function store(StoreAdvertRequest $request)
    {
        $valid_data =$request->validated();

        $assignment =Advert::create([
            'name'            =>ucwords($valid_data['name']),
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
    public function show(Advert $advert)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advert $advert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertRequest $request, Advert $advert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advert $advert)
    {
        //
    }
}
