<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Entities\District;
use App\Models\Entities\Gender;
use App\Models\Entities\MaritalStatus;
use App\Models\Entities\Region;
use App\Models\Entities\Ward;
use App\Models\Management\College;
use App\Models\Management\Customer;
use App\Models\Management\Hospital;
use App\Models\Management\Intern;
use App\Models\Management\Professional;
use App\Models\Management\Student;
use App\Models\User;
use App\Traits\CustomerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use CustomerTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $requests        = $request->all();
        $regions         = Region::get();
        $colleges        = College::get();
        $roles           = Role::whereNotIn('id', [1, 2])->get(['name', 'id']);
        $gender          = Gender::get();
        $maritial_status = MaritalStatus::get();
        $hospitals       = Hospital::orderBy('name')->get(['id', 'name']);
        $professionals  =Professional::orderBy('name')->get(['id', 'name']);


        return view('managements.customers.customers', compact(
            'regions',
            'colleges',
            'requests',
            'roles',
            'gender',
            'maritial_status',
            'hospitals',
            'professionals'
        ));
    }

    /**
     * DataTables endpoint.
     */
    public function getCustomersData(Request $request)
    {
        $requests = $request->all();
        $is_admin = Auth::user()->hasRole(['Admin', 'Super Admin']);

        $customers = Customer::with('region', 'district', 'ward', 'student', 'student.college', 'gender', 'user.roles')
            ->where(function ($query) use ($requests) {
                $query->whereDoesntHave('student')
                    ->orWhereHas('student', function ($subQuery) use ($requests) {
                        $subQuery->withfilters($requests);
                    });
            })
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->latest();

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('reg_date', function ($customer) {
                return date('d M Y', strtotime($customer->created_at));
            })
            ->addColumn('customer_info', function ($customer) {
                return '<div class="flex flex-col gap-0.5">
                            <span class="text-sm font-semibold text-slate-800">' . e($customer->customer_name) . '</span>
                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                <i class="bx bx-phone text-cyan-500"></i>' . e($customer->phone_number) . '</span>
                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                <i class="bx bx-envelope text-cyan-500"></i>' . e($customer->email) . '</span>
                        </div>';
            })
            ->addColumn('gender', function ($customer) {
                if ($customer->gender?->name == 'Male') {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200"><i class="bx bx-male mr-1"></i> Male</span>';
                }
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-700 border border-pink-200"><i class="bx bx-female mr-1"></i> ' . e($customer->gender?->name ?? 'N/A') . '</span>';
            })
            ->addColumn('id_number', function ($customer) {
                return '<span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded border border-slate-200">' . e($customer->id_number) . '</span>';
            })
            ->addColumn('address', function ($customer) {
                return $customer->address;
            })
            ->addColumn('customer_type', function ($customer) {
                return $customer->customer_reg_type;
            })
            ->addColumn('roles', function ($customer) use ($is_admin) {
                if (!$is_admin) {
                    return '';
                }
                $html = '';
                foreach ($customer->user?->roles ?? [] as $role) {
                    $badge = strtolower($role->name) == 'admin' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-cyan-100 text-cyan-700 border border-cyan-200';
                    $html .= '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mr-1 ' . $badge . '">' . e($role->name) . '</span>';
                }
                return $html;
            })
            ->addColumn('actions', function ($customer) use ($is_admin) {
                if (!$is_admin) {
                    return '';
                }
                return '<div class="btn-group">
                            <button type="button" class="inline-flex items-center gap-1 bg-slate-700 hover:bg-slate-800 text-white text-xs font-medium px-3 py-1.5 rounded-l-lg transition-colors">Actions</button>
                            <button type="button" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1.5 rounded-r-lg border-l border-slate-600 dropdown-toggle dropdown-toggle-split transition-colors" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-xl overflow-hidden">
                                <li>
                                    <a class="dropdown-item flex items-center gap-2 py-2 text-sm role-btn" data-bs-toggle="modal" data-bs-target="#roleModel" data-id="' . $customer->id . '" data-name="' . e($customer->customer_name) . '" data-email="' . e($customer->email) . '">
                                        <i class="bx bx-user-voice text-cyan-500"></i> Manage Roles
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="' . route('customers.show', $customer->uuid) . '">
                                        <i class="bx bx-user text-blue-500"></i> View Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item flex items-center gap-2 py-2 text-sm" href="' . route('customers.edit', $customer->uuid) . '">
                                        <i class="bx bx-edit text-emerald-500"></i> Edit Customer
                                    </a>
                                </li>
                            </ul>
                        </div>';
            })
            ->filterColumn('customer_info', function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('phone_number', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('id_number', function ($query, $keyword) {
                $query->where('id_number', 'like', "%{$keyword}%");
            })
            ->rawColumns(['customer_info', 'gender', 'id_number', 'address', 'roles', 'actions'])
            ->make(true);
    }

    /**
     * Excel report.
     */
    public function generateExcelReport(Request $request)
    {
        $requests  = $request->all();
        $customers = Customer::with('region', 'district', 'ward', 'student', 'student.college')
            ->whereHas('student', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->latest()
            ->cursor();

        return self::exportCustomerReport($customers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created customer (Web).
     */
    public function store(Request $request)
    {
        $valid = $this->validate($request, [
            'first_name'         => 'required|string|min:2|max:50',
            'middle_name'        => 'required|string|min:2|max:50',
            'last_name'          => 'required|string|min:2|max:50',
            'other_name'         => 'nullable|string|max:50',

            'phone_number'       => [
                'required',
                'digits:9',
                function ($attribute, $value, $fail) {
                    $prefixed = '255' . $value;
                    if (Customer::where('phone_number', $prefixed)->exists()) {
                        $fail('Phone number already registered as a customer.');
                    }
                    if (User::where('phone_number', $prefixed)->exists()) {
                        $fail('Phone number already registered as a user.');
                    }
                },
            ],

            'email'              => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (Customer::where('email', $value)->exists()) {
                        $fail('Email already registered as a customer.');
                    }
                    if (User::where('email', $value)->exists()) {
                        $fail('Email already registered as a user.');
                    }
                },
            ],

            'id_number'          => 'required|digits:20|unique:customers,id_number',
            // 'dob'                => 'required|date|before:today',
            'gender_id'          => 'required|exists:genders,id',
            // 'marital_status_id'  => 'nullable|exists:marital_statuses,id',
            'region_id'          => 'required|exists:regions,id',
            'district_id'        => 'required|exists:districts,id',
            'ward_id'            => 'required|exists:wards,id',
            'street'             => 'required|string|max:255',
            'resident_since'     => 'nullable|string|max:20',
            'reg_type'           => 'required|in:1,2',

            // Student-only (reg_type = 1)
            'college_id'         => 'required_if:reg_type,1|nullable|exists:colleges,id',
            'course'             => 'required_if:reg_type,1|nullable|string|max:255',
            'student_reg_id'     => 'required_if:reg_type,1|nullable|string|unique:students,student_reg_id',
            'index_no'           => 'required_if:reg_type,1|nullable|string|max:255',
            'study_year'         => 'required_if:reg_type,1|nullable|string|max:20',
            'heslb_status'       => 'required_if:reg_type,1|nullable|in:0,1',

            // Intern-only (reg_type = 2)
            'hospital_id'        => 'required_if:reg_type,2|nullable|exists:hospitals,id',
            'professional'       => 'required_if:reg_type,2|nullable|string|max:255',
            'start_date'         => 'required_if:reg_type,2|nullable|date',
            'end_date'           => 'required_if:reg_type,2|nullable|date|after_or_equal:start_date',
            'letter'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            $year = substr($valid['id_number'], 0, 4);
            $month = substr($valid['id_number'], 4, 2);
            $day = substr($valid['id_number'], 6, 2);
            $formattedDate = "$year-$month-$day";

            // 1) Customer record — inline, no StoreCustomer() from model
            $customer = Customer::create([
                'first_name'         => $valid['first_name'],
                'middle_name'        => $valid['middle_name'],
                'last_name'          => $valid['last_name'],
                'other_name'         => $valid['other_name'] ?? null,
                'phone_number'       => '255' . $valid['phone_number'],
                'email'              => $valid['email'],
                'id_number'          => $valid['id_number'],
                'dob'          => date('Y-m-d', strtotime($formattedDate)),
                'gender_id'          => $valid['gender_id'],
                // 'marital_status_id'  => $valid['marital_status_id'] ?? null,
                'region_id'          => $valid['region_id'],
                'district_id'        => $valid['district_id'],
                'ward_id'            => $valid['ward_id'],
                'street'             => $valid['street'],
                'resident_since'     => $valid['resident_since'] ?? null,
                'uuid'               => (string) Str::orderedUuid(),
                'customer_type'      => $valid['reg_type'],
            ]);

            // 2) User account
            $user = User::create([
                'name'         => ucwords(
                    $valid['first_name'] . ' ' .
                        $valid['middle_name'] . ' ' .
                        $valid['last_name']
                ),
                'email'        => $valid['email'],
                'phone_number' => '255' . $valid['phone_number'],
                'password'     => Hash::make('123456'),
                'uuid'         => (string) Str::orderedUuid(),
                'status'       =>'active',
                'customer_id'  => $customer->id,
            ]);
            $user->assignRole('Customer');

            // 3) Student record (reg_type = 1)
            if ((int) $valid['reg_type'] === 1) {
                Student::create([
                    'customer_id'        => $customer->id,
                    'college_id'         => $valid['college_id'],
                    'course'             => $valid['course'],
                    'student_reg_id'     => $valid['student_reg_id'],
                    'form_four_index_no' => $valid['index_no'],
                    'study_year'         => $valid['study_year'],
                    'heslb_status'       => $valid['heslb_status'],
                    'uuid'               => (string) Str::orderedUuid(),
                ]);
            }

            // 4) Intern record (reg_type = 2)
            if ((int) $valid['reg_type'] === 2) {
                $letterPath = null;

                if ($request->hasFile('letter')) {
                    $file     = $request->file('letter');
                    $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                        . '.' . $file->getClientOriginalExtension();
                    $letterPath = \Illuminate\Support\Facades\Storage::disk('public')
                        ->putFileAs('interns/letters', $file, $filename);
                }

                Intern::create([
                    'customer_id'  => $customer->id,
                    'hospital_id'  => $valid['hospital_id'],
                    'professional' => $valid['professional'],
                    'start_date'   => $valid['start_date'],
                    'end_date'     => $valid['end_date'],
                    'letter'       => $letterPath,
                    'uuid'         => (string) Str::orderedUuid(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer registered successfully',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Customer store failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register customer: ' . $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Cascade: districts for a region.
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
        ]);

        $districts = District::where('region_id', $request->region_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    /**
     * Cascade: wards for a district.
     */
    public function getWards(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
        ]);

        $wards = Ward::where('district_id', $request->district_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($wards);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $customer = Customer::with(
            'region',
            'district',
            'ward',
            'student',
            'student.college',
            'intern',
            'intern.hospital'
        )
        ->where('uuid', $uuid)
        ->firstOrFail();
    
        return view('managements.customers.profile', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $customer = Customer::with(
            'region', 'district', 'ward', 'marital_status',
            'student', 'student.college',
            'intern', 'intern.hospital'
        )
        ->where('uuid', $uuid)
        ->firstOrFail();
    
        $regions         = Region::get();
        $colleges        = College::get();
        $gender          = Gender::get();
        $maritial_status = MaritalStatus::get();
        $hospitals       = Hospital::orderBy('name')->get(['id', 'name']);
        $professionals   = Professional::orderBy('name')->get(['id', 'name']);
    
        return view('managements.customers.edit', compact(
            'customer',
            'gender',
            'maritial_status',
            'regions',
            'colleges',
            'hospitals',
            'professionals'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $valid = $this->validate($request, [
            'customer_id'        => 'required|exists:customers,uuid',
            'first_name'         => 'required|string|max:50',
            'middle_name'        => 'required|string|max:50',
            'last_name'          => 'required|string|max:50',
            'other_name'         => 'nullable|string|max:50',
    
            'gender'             => 'required|exists:genders,id',
            // 'maritial_status'    => 'nullable|exists:marital_statuses,id',
            'dob'                => 'required|date|before:today',
            'phone'              => 'required|digits:9',
            'email'              => 'required|email',
            'id_number'          => 'required|digits:20',
            'region_id'          => 'required|exists:regions,id',
            'district_id'        => 'required|exists:districts,id',
            'ward_id'            => 'required|exists:wards,id',
            'street'             => 'required|string|max:255',
            'resident_since'     => 'nullable|string|max:20',
            'reg_type'           => 'required|in:1,2',
    
            // Student-only (reg_type = 1)
            'college_id'         => 'required_if:reg_type,1|nullable|exists:colleges,id',
            'course'             => 'required_if:reg_type,1|nullable|string|max:255',
            'student_reg_id'     => 'required_if:reg_type,1|nullable|string',
            'form_four_index_no' => 'required_if:reg_type,1|nullable|string|max:255',
            'study_year'         => 'required_if:reg_type,1|nullable|string|max:20',
            'position'           => 'required_if:reg_type,1|nullable|string|max:255',
            'heslb_status'       => 'required_if:reg_type,1|nullable|in:0,1',
    
            // Intern-only (reg_type = 2)
            'hospital_id'        => 'required_if:reg_type,2|nullable|exists:hospitals,id',
            'professional'       => 'required_if:reg_type,2|nullable|string|max:255',
            'start_date'         => 'required_if:reg_type,2|nullable|date',
            'end_date'           => 'required_if:reg_type,2|nullable|date|after_or_equal:start_date',
            'letter'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
    
        try {
            DB::beginTransaction();
    
            $customer                    = Customer::where('uuid', $valid['customer_id'])->firstOrFail();
            $customer->first_name        = $valid['first_name'];
            $customer->middle_name       = $valid['middle_name'];
            $customer->last_name         = $valid['last_name'];
            $customer->other_name        = $valid['other_name'] ?? null;
            $customer->gender_id         = $valid['gender'];
            // $customer->marital_status_id = $valid['maritial_status'] ?? null;
            $customer->dob               = $valid['dob'];
            $customer->phone_number      = '255' . $valid['phone'];
            $customer->email             = $valid['email'];
            $customer->id_number         = $valid['id_number'];
            $customer->region_id         = $valid['region_id'];
            $customer->district_id       = $valid['district_id'];
            $customer->ward_id           = $valid['ward_id'];
            $customer->street            = $valid['street'];
            $customer->resident_since    = $valid['resident_since'] ?? null;
            $customer->customer_type     = $valid['reg_type'];
            $customer->save();
    
            // Student — reg_type = 1
            if ((int) $valid['reg_type'] === 1) {
                Student::updateOrCreate(
                    ['customer_id' => $customer->id],
                    [
                        'college_id'         => $valid['college_id'],
                        'course'             => $valid['course'],
                        'student_reg_id'     => $valid['student_reg_id'],
                        'form_four_index_no' => $valid['form_four_index_no'],
                        'study_year'         => $valid['study_year'],
                        'position'           => $valid['position'],
                        'heslb_status'       => $valid['heslb_status'],
                    ]
                );
    
                // Delete customer if already was intern
                Intern::where('customer_id', $customer->id)->delete();
            }
    
            // Intern — reg_type = 2
            if ((int) $valid['reg_type'] === 2) {
                $intern = Intern::firstOrNew(['customer_id' => $customer->id]);
    
                if ($request->hasFile('letter')) {
                    // Futa letter ya zamani kama ipo
                    if ($intern->letter && \Storage::disk('public')->exists($intern->letter)) {
                        \Storage::disk('public')->delete($intern->letter);
                    }
    
                    $file     = $request->file('letter');
                    $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                              . '.' . $file->getClientOriginalExtension();
                    $intern->letter = \Storage::disk('public')
                        ->putFileAs('interns/letters', $file, $filename);
                }
    
                $intern->customer_id  = $customer->id;
                $intern->hospital_id  = $valid['hospital_id'];
                $intern->professional = $valid['professional'];
                $intern->start_date   = $valid['start_date'];
                $intern->end_date     = $valid['end_date'];
                if (!$intern->uuid) {
                    $intern->uuid = (string) Str::orderedUuid();
                }
                $intern->save();
    
                // Delete student kama alikuwa student
                Student::where('customer_id', $customer->id)->delete();
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
            ], 200);
    
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Customer update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
