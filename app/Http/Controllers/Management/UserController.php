<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Models\Management\Agent;
use App\Models\Management\Customer;
use Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin','Super Admin','Agent']);
            })
            ->latest()
            ->get();
        $roles = Role::all();
        return view('managements.users.list', compact('users','roles'));
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
    public function store(UserStoreRequest $request)
    {
        $valid_data = $request->validated();

        $user = User::create([
            'name'  => ucwords($valid_data['name']),
            'email' => $valid_data['email'],
            'phone_number' => '255' . substr($valid_data['phone_number'], -9),
            'password'     => Hash::make(123456),
            'uuid'         => (string)Str::orderedUuid(),
        ]);

        if (!empty($valid_data['roles'])) {
            $user->assignRole($valid_data['roles']);
        }

        return response()->json([
            'success' => true,
            'message' => "Request Done Successfully"
        ], 200);
    }

    public function userStatus(Request $request)
    {
        $uuid   = $request->uuid;
        $action = $request->action;
        $status = ($action == "activate") ? 1 : 2;

        $user = User::where('uuid', $uuid)->first();
        $user->active    = $status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => "Request Done Successfully"
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
    public function edit(string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            abort(404);
        }

        $roles = Role::all();
        $permissions = Permission::all();

        return view('managements.users.edit', compact(
            'user',
            'roles',
            'permissions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function userUpdate(Request $request)
    {
        $valid_data = $this->validate($request, [
            'name'           => ['required', 'min:3', 'max:50'],
            'id'             => ['required'],
            'phone_number'   => ['required', 'min:9', 'max:12'],
            'roles'          => ['nullable', 'array'],
            'roles.*'        => ['string', 'exists:roles,name'],
            'permissions'    => ['nullable', 'array'],
            'permissions.*'  => ['string', 'exists:permissions,name'],
        ]);

        $user = User::where('uuid', $valid_data['id'])->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found'
            ], 404);
        }

        $user->update([
            'name' => ucwords($valid_data['name']),
            'phone_number' => '255' . substr($valid_data['phone_number'], -9)
        ]);

        $user->syncRoles($valid_data['roles'] ?? []);
        $user->syncPermissions($valid_data['permissions'] ?? []);

        return response()->json([
            'success' => true,
            'message' => "Request Done Successfully"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request  $request)
    {
        $uuid   = $request->uuid;

        $user   = User::where('uuid', $uuid)->first();
        $agent  = Agent::where('user_id', $user->id)->first();
        if ($agent) {
            $agent->delete();
        }
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => "Request Done Successfully"
        ], 200);
    }

    public function userUpdateRoles(Request $request)
    {

        DB::transaction(function () use ($request) {
            $customer = Customer::find($request->id);
            $user     = $customer->user ?? null;


            foreach ($request->role as $key => $value) {
                if ($value == 3) {
                    $college_id = $request->college_id;

                    $agent = Agent::updateOrCreate(
                        [
                            'college_id' => $college_id,
                            'user_id'    => $user->id,
                        ],
                        [
                            'student_reg_id' => $customer->student?->student_reg_id,
                            'image'          => $customer->image,
                            'uuid'           => (string)Str::orderedUuid(),
                            'deleted_at'     => null
                        ]
                    );
                }
            }


            if ($user) {
                $user->syncRoles($request->role);
            }
        });


        return response()->json([
            'success' => true,
            'message' => "Request Done Successfully"
        ], 200);
    }
}
