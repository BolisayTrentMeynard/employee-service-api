<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('department');

        if ($request->filled('search')) {
            $term = strtolower($request->search);
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(employee_number) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(first_name) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$term}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$term}%"])
                  ->orWhereHas('department', function ($dq) use ($term) {
                      $dq->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
                  });
            });
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        $employees = $query->paginate($request->get('per_page', 10));

        return EmployeeResource::collection($employees)->additional([
            'message' => 'Employees retrieved successfully',
        ]);
    }

    public function show(Employee $employee)
    {
        $employee->load('department');
        return (new EmployeeResource($employee))->additional([
            'message' => 'Employee retrieved successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'employee_number' => 'required|string|max:30|unique:employees,employee_number',
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'email' => 'required|email|unique:employees,email',
            'position' => 'required|string|max:100',
            'employment_status' => 'required|in:Active,Inactive',
        ]);

        $employee = Employee::create($validated);
        $employee->load('department');

        return (new EmployeeResource($employee))
            ->additional(['message' => 'Employee created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'department_id' => 'sometimes|required|exists:departments,id',
            'employee_number' => 'sometimes|required|string|max:30|unique:employees,employee_number,' . $employee->id,
            'first_name' => 'sometimes|required|string|max:80',
            'last_name' => 'sometimes|required|string|max:80',
            'email' => 'sometimes|required|email|unique:employees,email,' . $employee->id,
            'position' => 'sometimes|required|string|max:100',
            'employment_status' => 'sometimes|required|in:Active,Inactive',
        ]);

        $employee->update($validated);
        $employee->load('department');

        return (new EmployeeResource($employee))->additional([
            'message' => 'Employee updated successfully',
        ]);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully',
            'data' => null,
        ], 200);
    }
}