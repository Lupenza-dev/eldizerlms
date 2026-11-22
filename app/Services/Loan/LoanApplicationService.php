<?php

namespace App\Services\Loan;

use App\Models\Loan\EcoWaterApplicants;
use App\Models\Loan\LoanApplication;
use App\Models\Loan\LoanApproval;
use App\Models\Loan\LoanProduct;
use App\Models\Management\Customer;
use App\Models\Management\CustomerBankDetail;
use App\Models\Management\Device;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanApplicationService
{
    /**
     * Validation rules that depend on the selected product's configuration.
     */
    public static function rulesFor(LoanProduct $product): array
    {
        $amount = ['required', 'numeric', 'min:' . $product->min_amount];
        if ($product->max_amount) {
            $amount[] = 'max:' . $product->max_amount;
        }

        return [
            'amount'              => $amount,
            'agent_id'            => [$product->requires_agent ? 'required' : 'nullable', 'exists:agents,id'],
            'device_id'           => [$product->requires_device ? 'required' : 'nullable', 'exists:devices,id'],
            'eco_water_campus_id' => [$product->requires_eco_water_campus ? 'required' : 'nullable', 'exists:eco_water_campuses,id'],
            'initial_deposit'     => ['nullable', 'numeric', 'min:0'],
            'bank_name'           => ['nullable', 'string', 'max:255'],
            'account_number'      => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Create a loan application for the customer using the product configuration.
     *
     * @throws DomainException when the customer already has an active loan.
     */
    public function create(Customer $customer, LoanProduct $product, array $data): LoanApplication
    {
        // Cancel any initiated applications
        foreach ($customer->initiated_loan_application as $app) {
            $app->update(['level' => 'Canceled']);
            if ($app->loan_approval) {
                $app->loan_approval->update(['status' => 'Canceled']);
            }
        }

        if ($customer->loan_contracts()->count() > 0) {
            throw new DomainException('Customer has an active loan. Please close it first.');
        }

        $deviceId   = $data['device_id'] ?? null;
        $deposit    = $product->allows_initial_deposit ? (float) ($data['initial_deposit'] ?? 0) : 0;

        $calculator = LoanCalculatorService::calculator([
            'product'         => $product,
            'amount'          => $data['amount'],
            'device_name'     => $deviceId ? Device::find($deviceId)?->name : null,
            'device_id'       => $deviceId,
            'initial_deposit' => $deposit,
        ]);

        return DB::transaction(function () use ($customer, $product, $data, $calculator, $deviceId, $deposit) {
            $customerBankDetail = null;
            if (! empty($data['bank_name']) && ! empty($data['account_number'])) {
                $customerBankDetail = CustomerBankDetail::updateOrCreate(
                    [
                        'customer_id'    => $customer->id,
                        'account_number' => $data['account_number'],
                    ],
                    [
                        'bank_name' => $data['bank_name'],
                        'uuid'      => (string) Str::orderedUuid(),
                    ]
                );
            }

            $loanApplication = LoanApplication::create([
                'customer_id'             => $customer->id,
                'amount'                  => $calculator['amount'],
                'loan_amount'             => $calculator['total_amount'],
                'plan'                    => $calculator['plan'],
                'installment_amount'      => $calculator['installment_amount'],
                'interest_rate'           => $product->interest_rate,
                'interest_amount'         => $calculator['interest_amount'],
                'loan_code'               => (new LoanCalculatorService)->loanCode(),
                'uuid'                    => (string) Str::orderedUuid(),
                'level'                   => 'Application',
                'college_id'              => $customer->student?->college_id,
                'loan_product_id'         => $product->id,
                'loan_type'               => $product->id,
                'device_id'               => $deviceId,
                'initial_deposit'         => $deposit,
                'other_fees'              => $product->otherFeesJson(),
                'customer_bank_detail_id' => $customerBankDetail?->id,
                'is_mandate_sent'         => false,
            ]);

            if ($product->requires_agent) {
                LoanApproval::create([
                    'loan_application_id' => $loanApplication->id,
                    'agent_id'            => $data['agent_id'],
                    'uuid'                => (string) Str::orderedUuid(),
                ]);
            }

            if ($product->requires_eco_water_campus) {
                EcoWaterApplicants::create([
                    'loan_application_id' => $loanApplication->id,
                    'eco_water_campus_id' => $data['eco_water_campus_id'],
                ]);
            }

            return $loanApplication;
        });
    }
}
