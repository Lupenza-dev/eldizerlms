<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\HospitalStoreRequest;
use App\Models\Management\Hospital;
use App\Models\Management\HospitalContactPerson;
use App\Models\Entities\Region;
use App\Models\Entities\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hospitals = Hospital::with('contactPerson','region','district')->latest()->get();
        $regions = Region::with('districts')->orderBy('name')->get();
        return view('managements.hospitals.list', compact('hospitals','regions'));
    }

    public function getDistrictsByRegion($region_id)
    {
        $districts = District::where('region_id',$region_id)->orderBy('name')->get(['id','name']);
        return response()->json([
            'success' => true,
            'data'    => $districts,
        ]);
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
    public function store(HospitalStoreRequest $request)
    {
        $valid_data = $request->validated();

        $hospital = Hospital::create([
            'uuid'        => (string) Str::orderedUuid(),
            'name'        => ucwords($valid_data['name']),
            'short_name'  => ucwords($valid_data['short_name'] ?? ''),
            'region_id'   => $valid_data['region_id'],
            'district_id' => $valid_data['district_id'],
            'status'      => 'active',
            'created_by'  => Auth::user()->id,
        ]);

        HospitalContactPerson::create([
            'uuid'         => (string) Str::orderedUuid(),
            'hospital_id'  => $hospital->id,
            'name'         => ucwords($valid_data['contact_name']),
            'email'        => $valid_data['contact_email'] ?? null,
            'phone_number' => $valid_data['contact_phone'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hospital registered successfully',
        ], 200);
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

    public function hospitalUpdate(Request $request)
    {
        $valid_data = $this->validate($request, [
            'id'            => 'required',
            'name'          => 'required',
            'short_name'    => 'nullable',
            'region_id'     => 'required|exists:regions,id',
            'district_id'   => 'required|exists:districts,id',
            'contact_name'  => 'required',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'required',
        ]);

        $hospital = Hospital::where('uuid', $valid_data['id'])->first();

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found',
            ], 404);
        }

        $hospital->name        = ucwords($valid_data['name']);
        $hospital->short_name  = ucwords($valid_data['short_name'] ?? '');
        $hospital->region_id   = $valid_data['region_id'];
        $hospital->district_id = $valid_data['district_id'];
        $hospital->save();

        HospitalContactPerson::updateOrCreate(
            ['hospital_id' => $hospital->id],
            [
                'name'         => ucwords($valid_data['contact_name']),
                'email'        => $valid_data['contact_email'] ?? null,
                'phone_number' => $valid_data['contact_phone'],
                'uuid'         => (string) Str::orderedUuid(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Hospital updated successfully',
        ], 200);
    }

    public function hospitalStatus(Request $request)
    {
        $uuid   = $request->uuid;
        $action = $request->action;
        $status = ($action == "activate") ? "active" : "Inactive";

        $hospital = Hospital::where('uuid', $uuid)->first();

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found',
            ], 404);
        }

        $hospital->status = $status;
        $hospital->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $uuid = $request->uuid;

        $hospital = Hospital::where('uuid', $uuid)->first();

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found',
            ], 404);
        }

        $hospital->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hospital deleted successfully',
        ], 200);
    }
}
