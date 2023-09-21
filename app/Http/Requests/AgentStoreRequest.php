<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentStoreRequest extends FormRequest
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
            'name'           =>['required','min:3','max:50'],
            'email'          =>['required','unique:users,email'],
            'student_reg_id' =>['required','unique:agents,student_reg_id'],
            'phone_number'   =>['required','min:12','max:12'],
            'image'          =>['required'],
            'college_id'     =>['required']
        ];
    }
}
