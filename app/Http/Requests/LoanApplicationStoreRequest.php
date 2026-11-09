<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\AuthenticationException;

class LoanApplicationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 500));
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Unauthenticated',
        ], 401));
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount'          =>['required'],
            'plan'            =>['nullable'],
            'agent_id'        =>['nullable'],
            'loan_type'       =>['required_without:loan_product_id'],
            'loan_product_id' =>['nullable','integer'],
            'eco_water_campus_id' =>'nullable',
            // product-specific rules (agent/device/campus/amount limits) are enforced in LoanApplicationService::rulesFor()
        ];
    }
}
