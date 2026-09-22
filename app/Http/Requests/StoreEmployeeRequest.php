<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'employee_number' => ['required', 'string', 'max:30', 'unique:employees,employee_number'],
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'unique:employees,email'],
            'position' => ['required', 'string', 'max:100'],
            'employment_status' => ['required', 'in:Active,Inactive'],
        ];
    }
}