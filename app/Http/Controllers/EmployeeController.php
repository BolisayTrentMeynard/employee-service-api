<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
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

    public function store(StoreEmployeeRequest $request)
    {
        $validated = $request->validated();

        $employee = Employee::create($validated);
        $employee->load('department');

        return (new EmployeeResource($employee))
            ->additional(['message' => 'Employee created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();

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