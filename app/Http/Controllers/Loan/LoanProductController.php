<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use App\Models\Loan\LoanProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LoanProductController extends Controller
{
    private const BOOLEAN_FIELDS = [
        'has_fees_and_charges', 'fees_is_percentage', 'has_penalty', 'penalty_is_percentage',
        'requires_agent', 'requires_device', 'allows_initial_deposit',
        'requires_eco_water_campus', 'register_with_eco_water', 'is_active',
    ];

    public function index()
    {
        $products = LoanProduct::withCount('loanApplications')->orderBy('sort_order')->orderBy('name')->get();

        return view('managements.loan_products.list', [
            'products' => $products,
            'methods'  => LoanProduct::METHODS,
            'colors'   => LoanProduct::COLORS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        try {
            LoanProduct::create($data + ['created_by' => Auth::id()]);

            return response()->json([
                'success' => true,
                'message' => 'Loan product created successfully',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Loan product store failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create loan product.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(Request $request, LoanProduct $loanProduct)
    {
        $data = $this->validated($request, $loanProduct);

        try {
            $loanProduct->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Loan product updated successfully',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Loan product update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update loan product.',
                'debug'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function toggleStatus(LoanProduct $loanProduct)
    {
        $loanProduct->update(['is_active' => ! $loanProduct->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Loan product ' . ($loanProduct->is_active ? 'activated' : 'deactivated'),
        ], 200);
    }

    public function destroy(LoanProduct $loanProduct)
    {
        if ($loanProduct->loanApplications()->withTrashed()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is used by existing loan applications. Deactivate it instead.',
            ], 422);
        }

        try {
            $loanProduct->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loan product deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Loan product destroy failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete loan product',
            ], 500);
        }
    }

    private function validated(Request $request, ?LoanProduct $product = null): array
    {
        $request->merge(['code' => Str::upper(Str::slug((string) $request->input('code'), '_'))]);

        $rules = [
            'name'               => 'required|string|max:255',
            'code'               => ['required', 'string', 'max:50', Rule::unique('loan_products', 'code')->ignore($product?->id)],
            'description'        => 'nullable|string|max:2000',
            'icon'               => 'nullable|string|max:100',
            'color'              => ['nullable', Rule::in(array_keys(LoanProduct::COLORS))],
            'calculation_method' => ['required', Rule::in(array_keys(LoanProduct::METHODS))],
            // Rates are entered as percentages in the form (e.g. 20 = 20%) and stored as fractions.
            'markup_rate'        => 'nullable|numeric|min:0|max:1000',
            'fixed_total_amount' => 'nullable|required_if:calculation_method,fixed_amount|numeric|min:0',
            'plan_months'        => 'required|integer|min:1|max:120',
            'interest_rate'      => 'required|numeric|min:0|max:100',
            'fees_value'         => 'nullable|required_if:has_fees_and_charges,1|numeric|min:0',
            'penalty_value'      => 'nullable|required_if:has_penalty,1|numeric|min:0',
            'mandate_multiplier' => 'required|numeric|min:0.01|max:100',
            'min_amount'         => 'required|numeric|min:0',
            'max_amount'         => 'nullable|numeric|gte:min_amount',
            'sort_order'         => 'nullable|integer',
        ];
        foreach (self::BOOLEAN_FIELDS as $field) {
            $rules[$field] = 'nullable|boolean';
        }

        if ($request->boolean('fees_is_percentage')) {
            $rules['fees_value'] .= '|max:100';
        }
        if ($request->boolean('penalty_is_percentage')) {
            $rules['penalty_value'] .= '|max:100';
        }

        $data = $request->validate($rules);

        foreach (self::BOOLEAN_FIELDS as $field) {
            $data[$field] = $request->boolean($field);
        }

        $data['markup_rate']   = ($data['markup_rate'] ?? 0) / 100;
        $data['interest_rate'] = $data['interest_rate'] / 100;
        $data['fees_value']    = ($data['fees_value'] ?? 0) / ($data['fees_is_percentage'] ? 100 : 1);
        $data['penalty_value'] = ($data['penalty_value'] ?? 0) / ($data['penalty_is_percentage'] ? 100 : 1);
        $data['sort_order']    = $data['sort_order'] ?? 0;

        if ($data['calculation_method'] !== LoanProduct::METHOD_FIXED_AMOUNT) {
            $data['fixed_total_amount'] = null;
        }

        return $data;
    }
}
