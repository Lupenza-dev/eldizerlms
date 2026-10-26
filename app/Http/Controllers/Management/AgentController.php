<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Management\Agent;
use App\Models\User;
use App\Models\Management\College;
use App\Http\Requests\AgentStoreRequest;
use Carbon\Carbon;
use Str;
use DB;
use Storage;
use Hash;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agents   = Agent::with(['user', 'colleges'])->latest()->get();
        $colleges = College::orderBy('name', 'ASC')->get();
    
        return view('managements.agents.list', compact('agents', 'colleges'));
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
    public function store(AgentStoreRequest $request)
    {
        $valid_data = $request->validated();
        $path = null;

        try {
            if ($request->hasFile('image')) {
                $file     = $request->file('image');
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                            . '.' . $file->getClientOriginalExtension();
                $path = Storage::disk('public')->putFileAs('attachments', $file, $filename);
            }

            $agent = DB::transaction(function () use ($valid_data, $path) {
                $user = User::create([
                    'name'         => ucwords($valid_data['name']),
                    'email'        => $valid_data['email'],
                    'phone_number' => $valid_data['phone_number'],
                    'password'     => Hash::make('123456'),
                    'uuid'         => (string) Str::orderedUuid(),
                ]);

                $user->assignRole('Agent');

                $agent = Agent::create([
                    'student_reg_id' => $valid_data['student_reg_id'],
                    'user_id'        => $user->id,
                    'image'          => $path,
                    'uuid'           => (string) Str::orderedUuid(),
                ]);

                // Many-to-many: attach colleges
                $agent->colleges()->attach($valid_data['college_ids']);

                return $agent;
            });

            return response()->json([
                'success' => true,
                'message' => 'Agent registered successfully',
            ], 201);

        } catch (\Throwable $e) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            \Log::error('Agent store failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data'  => $valid_data,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register agent. Please try again.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function agentUpdate(Request $request)
    {
        $valid_data = $this->validate($request, [
            'name'           => ['required', 'min:3', 'max:50'],
            'id'             => ['required', 'exists:agents,uuid'],
            'student_reg_id' => ['required'],
            'phone_number'   => ['required', 'min:10', 'max:12'],
            'college_ids'    => ['required', 'array', 'min:1'],
            'college_ids.*'  => ['integer', 'exists:colleges,id'],
            'email'          => ['required', 'email'],
        ]);

        try {
            DB::transaction(function () use ($valid_data) {
                $agent = Agent::where('uuid', $valid_data['id'])->firstOrFail();

                $agent->update([
                    'student_reg_id' => $valid_data['student_reg_id'],
                ]);

                $agent->user->update([
                    'name'         => ucwords($valid_data['name']),
                    'phone_number' => $valid_data['phone_number'],
                    'email'        => $valid_data['email'],
                ]);

                // Many-to-many: sync (detach old, attach new)
                $agent->colleges()->sync($valid_data['college_ids']);
            });

            return response()->json([
                'success' => true,
                'message' => 'Request Done Successfully',
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('Agent update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data'  => $valid_data,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update agent.',
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
