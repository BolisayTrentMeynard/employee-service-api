<?php

namespace App\Http\Controllers;

use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Departments retrieved successfully',
            'data' => Department::all(),
        ]);
    }
}