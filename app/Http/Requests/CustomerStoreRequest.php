<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Auth\AuthenticationException;

class CustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

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
            'first_name'  =>['required','max:50','min:2'],
            'middle_name' =>['required','max:50','min:2'],
            'last_name'   =>['required','max:50','min:2'],
            'phone_number'=>['required','max:9','min:9','unique:customers,phone_number'],
            'id_number'    =>['required','max:20','min:20','unique:customers,id_number'],
            'email'       =>['required','unique:users,email'],
           // 'dob'         =>'required',
           // 'gender_id'   =>'required',
            //'marital_status_id' =>'required',
            'region_id'         =>'required',
            'district_id'       =>'required',
            'ward_id'           =>'required',
            'street'            =>'required',
            'resident_since'    =>'required',
           // 'image'             =>'required',
            'student_reg_id' =>['required','unique:students,student_reg_id'],
            'college_id'     =>'required',
            //'position'       =>'required',
            'study_year'     =>'required',
            'heslb_status'   =>'required',
            'course'         =>'required',
            'index_no'       =>'required',
        ];
    }
}
