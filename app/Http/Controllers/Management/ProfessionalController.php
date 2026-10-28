<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Management\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfessionalController extends Controller
{
    public function index()
    {
        $professionals =Professional::latest()->get();
        return view('managements.professionals.list', compact('professionals'));
    }

    public function store(Request $request)
    {
        $valid_data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        try {
            Professional::create([
                'name'        => $valid_data['name'],
                'description' => $valid_data['description'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Professional created successfully',
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Professional store failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Professional $professional)
    {
        $valid_data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        try {
            $professional->update([
                'name'        => $valid_data['name'],
                'description' => $valid_data['description'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Professional updated successfully',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Professional update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Professional $professional)
    {
        try {
            $professional->delete();

            return response()->json([
                'success' => true,
                'message' => 'Professional deleted successfully',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Professional destroy failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete professional',
            ], 500);
        }
    }

    public function appApi(){
        
    }
}