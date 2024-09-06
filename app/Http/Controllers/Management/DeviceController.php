<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\Device;
use App\Models\Management\DeviceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Str;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories =DeviceCategory::get();
        $devices    =Device::with('device_category')->get();
        return view('managements.devices.list',compact('categories','devices'));
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
                'name' => 'required',
                'price' => 'required',
                'plan' => 'required',
                'initial_deposit' => 'required',
                'device_category' => 'required',
                'image' => 'required',
            ]
        );

       
        $device =Device::create([
            'name'            =>$valid_data['name'],
            'price'           =>$valid_data['price'],
            'plan'            =>$valid_data['plan'],
            'initial_deposit' =>$valid_data['initial_deposit'],
            'device_category_id' =>$valid_data['device_category'],
            'user_id'        =>Auth::user()->id,
            'uuid'           =>(string)Str::orderedUuid(),
        ]);

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filename =time().$file->getClientOriginalName();
            $path     =Storage::disk('local')->putFileAs('',$file,$filename);
            
            $device->image =$path;
            $device->save();
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

    public function destroyDevice(Request $request){
        $uuid =$request->uuid;

        Device::where('uuid',$uuid)->delete();

        return response()->json([
            'success' =>true,
            'message' =>"Request Done Successfully"
        ],200);
    }
}
