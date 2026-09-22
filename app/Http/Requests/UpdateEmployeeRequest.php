<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // was false — that's what was 403-blocking every update
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')->id;

        return [
            'department_id' => ['sometimes', 'required', 'exists:departments,id'],
            'employee_number' => ['sometimes', 'required', 'string', 'max:30', 'unique:employees,employee_number,' . $employeeId],
            'first_name' => ['sometimes', 'required', 'string', 'max:80'],
            'last_name' => ['sometimes', 'required', 'string', 'max:80'],
            'email' => ['sometimes', 'required', 'email', 'unique:employees,email,' . $employeeId],
            'position' => ['sometimes', 'required', 'string', 'max:100'],
            'employment_status' => ['sometimes', 'required', 'in:Active,Inactive'],
        ];
    }
}