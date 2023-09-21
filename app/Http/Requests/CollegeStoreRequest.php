<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollegeStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'     =>['required','unique:colleges,name'],
            'location' =>['required'],
            'logo'     =>['required'],
            'rep_name' =>['required'],
            'rep_phone_number' =>['required'],
            'position'         =>['required'],
        ];
    }
}
