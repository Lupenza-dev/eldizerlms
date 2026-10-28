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
        $valid_data = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:5000',
            'price'           => 'required|numeric|min:0',
            'plan'            => 'required|integer|min:1',
            'initial_deposit' => 'required|numeric|min:0',
            'device_category' => 'required|exists:device_categories,id',
            'images'          => 'required|array|min:1',
            'images.*'        => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
    
        $imagePaths = [];
    
        try {
            DB::beginTransaction();
    
            $device = Device::create([
                'name'               => $valid_data['name'],
                'description'        => $valid_data['description'] ?? null,
                'price'              => $valid_data['price'],
                'plan'               => $valid_data['plan'],
                'initial_deposit'    => $valid_data['initial_deposit'],
                'device_category_id' => $valid_data['device_category'],
                'user_id'            => Auth::id(),
                'uuid'               => (string) Str::orderedUuid(),
            ]);
    
            foreach ($request->file('images') as $file) {
                $filename = time() . '_' . Str::random(8) . '_'
                            . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                            . '.' . $file->getClientOriginalExtension();
    
                $path = Storage::disk('local')->putFileAs('', $file, $filename);
                if ($path) {
                    $imagePaths[] = $path;
                }
            }
    
            $device->images = $imagePaths;
            $device->save();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Device registered successfully',
            ], 201);
    
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($imagePaths as $p) {
                if (Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }
            Log::error('Device store failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to register device. Please try again.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
    
    public function update(Request $request, Device $device)
    {
        $valid_data = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:5000',
            'price'           => 'required|numeric|min:0',
            'plan'            => 'required|integer|min:1',
            'initial_deposit' => 'required|numeric|min:0',
            'device_category' => 'required|exists:device_categories,id',
            'images'          => 'nullable|array',
            'images.*'        => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'remove_images'   => 'nullable|array',
            'remove_images.*' => 'string',
        ]);
    
        // $device tayari imepatikana — hakuna haja ya 'id' validation
    
        $existingImages = $device->images ?? [];
        $newPaths       = [];
    
        try {
            DB::beginTransaction();
    
            $toRemove = $valid_data['remove_images'] ?? [];
            foreach ($toRemove as $path) {
                if (in_array($path, $existingImages)) {
                    Storage::disk('public')->delete($path);
                }
            }
            $existingImages = array_values(array_diff($existingImages, $toRemove));
    
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . Str::random(8) . '_'
                                . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                                . '.' . $file->getClientOriginalExtension();
    
                    $path = Storage::disk('public')->putFileAs('devices', $file, $filename);
                    if ($path) {
                        $existingImages[] = $path;
                        $newPaths[]       = $path;
                    }
                }
            }
    
            if (empty($existingImages)) {
                throw new \Exception('Device must have at least one image.');
            }
    
            $device->update([
                'name'               => $valid_data['name'],
                'description'        => $valid_data['description'] ?? null,
                'price'              => $valid_data['price'],
                'plan'               => $valid_data['plan'],
                'initial_deposit'    => $valid_data['initial_deposit'],
                'device_category_id' => $valid_data['device_category'],
                'images'             => $existingImages,
            ]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Device updated successfully',
            ], 200);
    
        } catch (\Throwable $e) {
            DB::rollBack();
            foreach ($newPaths as $p) {
                if (Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }
            Log::error('Device update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
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
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        try {
            if (is_array($device->images)) {
                foreach ($device->images as $path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }
    
            $device->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Device deleted successfully',
            ], 200);
    
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Device destroy failed: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete device: ' . $e->getMessage(),
            ], 500);
        }
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
