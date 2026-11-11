<?php

namespace App\Http\Controllers\Loan;

use App\Event\LoanApplied;
use App\Http\Controllers\Controller;
use App\Models\Entities\Region;
use App\Models\Loan\EcoWaterApplicants;
use Illuminate\Http\Request;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanContract;
use App\Models\Loan\Installment;
use App\Models\Loan\LoanApproval;
use App\Models\Management\Agent;
use App\Models\Management\College;
use App\Models\Management\Customer;
use App\Models\Management\CustomerBankDetail;
use App\Models\Management\CustomerMandate;
use App\Models\Management\Device;
use App\Models\Management\EcoWaterCampus;
use App\Models\Payment\DisbursmentPayment;
use App\Services\Loan\LoanCalculatorService;
use Auth;
use DB;
use Str;
use App\Traits\LoanTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Validator;

class LoanApplicationController extends Controller
{
    use LoanTrait;

    public function index(Request $request)
    {
        $regions   = Region::get();
        $colleges  = College::get();
        $requests  = $request->all();
        $filter   = Auth::user()->hasRole('Agent') ? true : false;
        $loans = LoanApplication::with('customer', 'customer.student')
            ->where('level', '!=', 'Canceled')
            ->where('level', '!=', 'GRANTED')
            ->where('level', '!=', 'CLOSED')
            ->whereHas('customer', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->where(function ($query) use ($requests, $filter) {
                $query->whereDoesntHave('customer.student')
                    ->orWhereHas('customer.student', function ($subQuery) use ($requests, $filter) {
                        $subQuery->withfilters($requests);
                        if ($filter) {
                            $subQuery->where('college_id', getCollegeId());
                        }
                    });
            })
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->latest()
            ->get();


        $devices  = Device::orderBy('name')->get(['id', 'name', 'price', 'initial_deposit']);
        $agents   = Agent::with('user')->get();
        $campuses = EcoWaterCampus::orderBy('campus_name')->get(['id', 'campus_name']);
        return view('loans.loan_applications', compact('loans', 'requests', 'regions', 'colleges', 'devices', 'agents', 'campuses'));
    }





    public function confirmMandate(Request $request)
    {
        $valid = $this->validate($request, [
            'loan_uuid'  => 'required',
            'created_at' => 'required|date',
        ]);

        $loanApplication = LoanApplication::where('uuid', $valid['loan_uuid'])->first();

        // sent mandate confirmation

        $customer_bank  = CustomerBankDetail::where('id', $loanApplication->customer_bank_detail_id)
            // ->where('bank_name','CRDB')
            ->first();

        if ($customer_bank) {
            $token_request = getApiToken();
            Log::info('-----result---data needed now');
            Log::info($token_request['data']);

            

            $payload = [
                // 'corporate_reference' =>$token_request['data']['corporate_reference'],
                'channel'             => 'BANK',
                'payment_channel'      => $customer_bank->bank_name,
                // 'account_name'        =>'Luhangano Lupenza',
                'account_name'        => $loanApplication->customer?->customer_name,
                'account_number'    => $customer_bank->account_number,
                // 'account_number'    =>'0152784219300',
                'phone'             => '0' . substr($loanApplication->customer?->phone_number ?? '', -9),
                'NIN'               => $loanApplication->customer?->id_number,
                'address'           => $loanApplication->customer?->region?->name,
                'total_amount'       => ($loanApplication->loan_type === 3 || $loanApplication->loan_type === 2) ? $loanApplication->loan_amount : $loanApplication->loan_amount * 2,
                'debit_type'        => 'FIXED',
                'min_installment_amount' => $loanApplication->installment_amount,
                'installment_amount'    => $loanApplication->installment_amount,
                'periodicity'       => 'MONTHLY',
                'deduction_start_date' => $valid['created_at'],
            ];

            $customer_mandate = CustomerMandate::create([
                'customer_id' => $loanApplication->customer_id,
                'loan_application_id' => $loanApplication->id,
                'payload' => json_encode($payload),
                'uuid' => (string)Str::orderedUuid(),
            ]);

            try {
                $response = Http::withToken($token_request['data']['token'])
                    ->post(env('SOLOCODE_BASE_URL') . '' . 'mandate/create', $payload);
                $result = json_decode($response, true);
                Log::info("++++++++++Result of mandate++++++++++++");
                Log::info($result);
                if ($result['success']) {
                    $customer_mandate->update([
                        'mandate_reference' => $result['data']['mandate_reference'],
                        'status'     => $result['data']['status'],
                    ]);

                    $loanApplication->update([
                        'is_mandate_sent' => true,
                        'mandate_date' => $valid['created_at'],
                        'mandate_sent_date' => now(),
                        'mandate_created_by' => auth()->id(),
                    ]);

                    if ($loanApplication->loan_type == 3) {
                        $registration = $this->registerEcowaterStudent($loanApplication);
                        if (! $registration['success']) {
                            return response()->json([
                                'success' => false,
                                'errors' => 'Mandate created successfully, but ' . $registration['message'],
                            ], 502);
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Mandate Created Successfully',
                    ], 200);
                } else {
                    Log::error('Error Mandate creation Error: ' . $result);
                    return response()->json([
                        'success' => false,
                        'errors' => 'Error creating mandate: ' . $result['message'],
                    ], 500);
                }
            } catch (\Throwable $th) {
                Log::error('Error Mandate creation Error: ' . $th->getMessage());
                // return ['success'=>false,'message'=>$th->getMessage()];
                return response()->json([
                    'success' => false,
                    'errors' => 'Error creating mandate: ' . $th->getMessage(),
                ], 500);
            }
        } else {

            return response()->json([
                'success' => false,
                'errors' => 'Customer bank detail not found',
            ], 500);
        }
    }

    public function waitingMandateConfirmation(Request $request)
    {
        $regions   = Region::get();
        $colleges  = College::get();
        $requests  = $request->all();
        $filter   = Auth::user()->hasRole('Agent') ? true : false;
        $loans = LoanApplication::with('customer', 'customer.student')
            ->where('is_mandate_sent', false)
            ->where('level', '!=', 'Canceled')
            ->where('level', '!=', 'GRANTED')
            ->where('level', '!=', 'CLOSED')
            ->whereHas('customer', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->where(function ($query) use ($requests, $filter) {
                $query->whereDoesntHave('customer.student')
                    ->orWhereHas('customer.student', function ($subQuery) use ($requests, $filter) {
                        $subQuery->withfilters($requests);
                        if ($filter) {
                            $subQuery->where('college_id', getCollegeId());
                        }
                    });
            })
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->latest()
            ->get();

        $devices  = Device::orderBy('name')->get(['id', 'name', 'price', 'initial_deposit']);
        $agents   = Agent::with('user')->get();
        $campuses = EcoWaterCampus::orderBy('campus_name')->get(['id', 'campus_name']);


        // \Log::debug([
        //     'loans_count'    => $loans->count(),
        //     'devices_count'  => $devices->count(),
        //     'agents_count'   => $agents->count(),
        //     'campuses_count' => $campuses->count(),
        //     'campuses_sample' => $campuses->take(3)->toArray(),
        //     'devices_sample'  => $devices->take(3)->toArray(),
        //     'agents_sample'   => $agents->take(3)->map(fn($a) => ['id' => $a->id, 'user_name' => $a->user?->name])->toArray(),
        // ]);        

        $page_title = 'Loan Application Waiting Mandate Confirmation';
        $search_route = route('loan.applications.waiting.mandate');
        return view('loans.loan_applications', compact('loans', 'requests', 'regions', 'colleges', 'page_title', 'search_route', 'agents', 'devices', 'campuses'));
    }


    /**
     * Create loan application from portal (officer on behalf of customer)
     */
    public function storeWebLoan(Request $request)
    {

        // \Log::debug('=== storeWebLoan REQUEST ===');
        // \Log::debug($request->all());
        $valid = $request->validate([
            'customer_uuid'       => 'required|exists:customers,uuid',
            'amount'              => 'required|numeric|min:1000',
            'loan_type'           => 'required|in:1,2,3',
            'device_id'           => 'required_if:loan_type,2|nullable|exists:devices,id',
            'agent_id'            => 'required_if:loan_type,1,2|nullable|exists:agents,id',
            'eco_water_campus_id' => 'required_if:loan_type,3|nullable|exists:eco_water_campuses,id',
            'initial_deposit'     => 'nullable|numeric|min:0',
            'bank_name'           => 'nullable|string|max:255',
            'account_number'      => 'nullable|string|max:255',
        ]);

        $customer = Customer::where('uuid', $valid['customer_uuid'])->firstOrFail();

        // Cancel any active applications
        if ($customer->initiated_loan_application()->exists()) {
            foreach ($customer->initiated_loan_application as $app) {
                $app->update(['level' => 'Canceled']);
                if ($app->loan_approval) {
                    $app->loan_approval->update(['status' => 'Canceled']);
                }
            }
        }

        if ($customer->loan_contracts()->count() > 0) {
            return response()->json([
                'success' => false,
                'errors'  => 'Customer has an active loan. Please close it first.',
            ], 422);
        }

        $device_name = null;
        if ($request->device_id) {
            $device_name = Device::find($request->device_id)?->name;
        }

        $calculator = LoanCalculatorService::calculator([
            'amount'      => $valid['amount'],
            'plan'        => 3,
            'loan_type'   => $valid['loan_type'],
            'device_name' => $device_name,
            'device_id'   => $request->device_id,
            'initial_deposit' => $request->input('initial_deposit', 0),
        ]);

        $code_generator = new LoanCalculatorService;

        $customerBankDetail = null;
        if ($request->bank_name && $request->account_number) {
            $customerBankDetail = CustomerBankDetail::updateOrCreate(
                [
                    'customer_id'    => $customer->id,
                    'account_number' => $request->account_number,
                ],
                [
                    'bank_name' => $request->bank_name,
                    'uuid'      => (string) Str::orderedUuid(),
                ]
            );
        }

        $loan_application = LoanApplication::create([
            'customer_id'             => $customer->id,
            'amount'                  => $calculator['amount'],
            'loan_amount'             => $calculator['total_amount'],
            'plan'                    => $valid['loan_type'] == 3 ? 1 : 3,
            'installment_amount'      => $calculator['installment_amount'],
            'interest_rate'           => 0.035,
            'interest_amount'         => $calculator['interest_amount'],
            'loan_code'               => $code_generator->loanCode(),
            'uuid'                    => (string) Str::orderedUuid(),
            'level'                   => 'Application',
            'college_id'              => $customer->student?->college_id,
            'loan_type'               => $valid['loan_type'],
            'device_id'               => $request->device_id ?? null,
            'initial_deposit'         => $request->initial_deposit ?? 0,
            'other_fees'              => json_encode(['fees_and_charges' => 0.095, 'late_payment' => 0.05]),
            'customer_bank_detail_id' => $customerBankDetail?->id,
            'is_mandate_sent'         => false,
        ]);

        if (in_array((int) $valid['loan_type'], [1, 2])) {
            LoanApproval::create([
                'loan_application_id' => $loan_application->id,
                'agent_id'            => $valid['agent_id'],
                'uuid'                => (string) Str::orderedUuid(),
            ]);
        }

        if ((int) $valid['loan_type'] === 3) {
            EcoWaterApplicants::create([
                'loan_application_id' => $loan_application->id,
                'eco_water_campus_id' => $valid['eco_water_campus_id'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Loan application created successfully',
        ], 201);
    }

    /**
     * Calculator preview for web modal
     */
    public function webLoanCalculator(Request $request)
    {
        $valid = $request->validate([
            'amount'    => 'required|numeric|min:1000',
            'loan_type' => 'required|in:1,2,3',
            'device_id' => 'nullable|exists:devices,id',
        ]);

        $device_name = $request->device_id
            ? Device::find($request->device_id)?->name
            : null;

        $calculator = LoanCalculatorService::calculator([
            'amount'      => $valid['amount'],
            'plan'        => 3,
            'loan_type'   => $valid['loan_type'],
            'device_name' => $device_name,
            'device_id'   => $request->device_id,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $calculator,
        ]);
    }

    public function generateExcelReport(Request $request)
    {
        $requests  = $request->all();
        $loans = LoanApplication::with('customer', 'customer.student', 'college', 'customer.region', 'customer.district', 'customer.ward')
            ->where('level', '!=', 'Canceled')
            ->where('level', '!=', 'GRANTED')
            ->where('level', '!=', 'CLOSED')
            ->whereHas('customer', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->whereHas('customer.student', function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->when($requests, function ($query) use ($requests) {
                $query->withfilters($requests);
            })
            ->cursor();

        return self::exportLoanApplicationReport($loans);
    }

    public function profile($uuid)
    {
        $loan = LoanApplication::with('customer', 'guarantors', 'customer_mandate')->where('uuid', $uuid)->first();
        // return $loan;
        return view('loans.loan_application_profile', compact('loan'));
    }

    public function rejectApplication(Request $request)
    {
        $loan_uuid  = $request->loan_uuid;
        $remark     = $request->remark;

        $status   = Auth::user()->hasRole('Agent') ? "Rejected by Agent" : "Rejected by Admin";

        $loan = LoanApplication::where('uuid', $loan_uuid)->first();
        $loan->remark = $remark;
        $loan->level  = $status;
        $loan->attended_by = Auth::user()->id;
        $loan->save();

        $loan_approval = LoanApproval::where('loan_application_id', $loan->id)->first();
        if ($loan_approval) {
            $loan_approval->status = $status;
            $loan_approval->remark = $remark;
            $loan_approval->attended_date = Carbon::now();
            $loan_approval->save();
        }

        event(new LoanApplied($loan, 3));

        return response()->json([
            'success'  => true,
            'message'  => "The Action Done Successfully",
        ]);
    }

    public function agentApproveApplication(Request $request)
    {
        $loan_uuid  = $request->loan_uuid;
        $remark     = $request->remark;

        $status   = "Approved by Agent";

        $loan = LoanApplication::where('uuid', $loan_uuid)->first();
        $loan->remark = $remark;
        $loan->level  = $status;
        $loan->attended_by = Auth::user()->id;
        $loan->save();

        $loan_approval = LoanApproval::where('loan_application_id', $loan->id)->first();
        if ($loan_approval) {
            $loan_approval->status = $status;
            $loan_approval->remark = $remark;
            $loan_approval->attended_date = Carbon::now();
            $loan_approval->save();
        }

        event(new LoanApplied($loan, 2));


        return response()->json([
            'success'  => true,
            'message'  => "The Action Done Successfully",
        ]);
    }

    public function approveApplication(Request $request)
    {
        $valid = $this->validate($request, [
            'payment_date' => 'required',
            'payment_reference' => ['required', 'unique:disbursment_payments,payment_reference'],
            'paid_amount'       => 'required',
            'payment_channel'   => 'required',
            'payment_method'    => 'required',
            'loan_uuid'         => 'required',
        ]);

        $loan = LoanApplication::where('uuid', $valid['loan_uuid'])->where('level', '!=', 'GRANTED')->first();

        if (!$loan) {
            return response()->json([
                'success' => false,
                'errors'  => 'Customer Doesnot Has Active Loan Application',
            ], 500);
        }

        if ($valid['paid_amount'] < $loan->amount) {
            return response()->json([
                'success' => false,
                'errors'  => 'Disbursed Amount is Less Than applied Amount',
            ], 500);
        }

        $outstanding_loan = $loan->customer->outstanding_loan;

        if ($outstanding_loan) {
            return response()->json([
                'success' => false,
                'errors'  => 'Customer Has Outstanding Loan',
            ], 500);
        }

        try {
            DB::transaction(function () use ($valid) {
                $loan = LoanApplication::where('uuid', $valid['loan_uuid'])->first();
                $loan_contract = LoanContract::create([
                    'customer_id' => $loan->customer_id,
                    'loan_application_id'      => $loan->id,
                    'college_id'               => $loan->college_id,
                    'amount'                   => $loan->amount,
                    'loan_amount'              => $loan->loan_amount,
                    'installment_amount'       => $loan->installment_amount,
                    'plan'       => $loan->plan,
                    'status'       => "GRANTED",
                    'current_balance'           => 0,
                    'outstanding_amount'        => $loan->loan_amount,
                    'contract_code'             => $loan->loan_code,
                    'interest_rate'             => $loan->interest_rate,
                    'interest_amount'           => $loan->interest_amount,
                    'fees_amount'               => $loan->fees_amount,
                    'start_date'                => $valid['payment_date'],
                    'expected_end_date'         => date('Y-m-d', strtotime("+" . $loan->plan . " months", strtotime($valid['payment_date']))),
                    'created_by'                => Auth::user()->id,
                    'uuid'                      => (string)Str::orderedUuid(),
                    'other_fees'                 => $loan->other_fees
                ]);

                // for ($i=1; $i < $loan->plan; $i++) {
                $i = 1;
                $installment = Installment::create([
                    'loan_contract_id' => $loan_contract->id,
                    'installment_order' => $i,
                    'total_amount'      => $loan_contract->installment_amount,
                    'current_balance'   => 0,
                    'outstanding_amount' => $loan_contract->installment_amount,
                    'payment_type'       => "Installment Amount",
                    'payment_date'       => date('Y-m-d', strtotime("+" . $i . " months", strtotime($loan_contract->start_date))),
                    'last_paid_amount'   => 0,
                    'status'             => "OPEN",
                    'uuid'               => (string)Str::orderedUuid(),
                ]);
                // }

                $payment = DisbursmentPayment::create([
                    'loan_contract_id'    => $loan_contract->id,
                    'loan_application_id' => $loan->id,
                    'payment_reference'   => $valid['payment_reference'],
                    'paid_amount'         => $valid['paid_amount'],
                    'payment_date'      => $valid['payment_date'],
                    'payment_channel'   => $valid['payment_channel'],
                    'payment_method'    => $valid['payment_method'],
                    'created_by'        => Auth::user()->id,
                    'uuid'              => (string)Str::orderedUuid(),
                ]);
            });

            $loan->level = "GRANTED";
            $loan->save();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return response()->json([
            'success' => true,
            'message' => "Request Done Successfully"
        ], 200);
    }

    public function loanManagement()
    {
        return view('loans.loan_management');
    }

    public function registerEcowaterStudent($loanApplication)
    {
        $token = env('ECO_TOKEN');
        $sender = env('ECO_SENDER');
        $base_url = env('ECO_BASE_URL');

        $payload = [
            'full_name' => $loanApplication?->customer?->customer_name,
            'gender' => $loanApplication?->customer?->gender_id == 1 ? 'M' : 'F',
            'mobile' => $loanApplication?->customer?->phone_number,
            'email' => $loanApplication?->customer?->email,
            'campus' => $loanApplication?->ecoWaterApplicant?->ecoWaterCampus?->campus_code ?? null,
            'amount' => $loanApplication?->amount,
        ];

        try {
            $response = Http::acceptJson()->asJson()
                ->withHeaders(['auth-token' => $token, 'sender' => $sender])
                ->connectTimeout(10)
                ->timeout(30)
                ->post(rtrim($base_url, '/') . '/student_register', $payload);
        } catch (ConnectionException $e) {
            Log::error('EcoWater registration failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'EcoWater registration could not be confirmed. Check EcoWater before retrying.'];
        }

        $data = $response->json();

        $loanApplication->ecoWaterApplicant->update(
            [
                'registration_code' => $data['data']['registration_code'],
                'remarks' => $data['data']['remarks'] ?? null
            ]
        );

        $loanApplication->customer->update([
            'is_eco_water_eligible' => true,
        ]);

        return ['success' => true, 'data' => $data['data']];
    }
}
