<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HospitalStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'short_name'   => 'nullable|string|max:255',
            'region_id'    => 'required|exists:regions,id',
            'district_id'  => 'required|exists:districts,id',
            'contact_name' => 'required|string|max:255',
            'contact_email'=> 'nullable|email|max:255',
            'contact_phone'=> 'required|string|max:255',
        ];
    }
}
