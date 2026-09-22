<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

Route::get('/departments', [DepartmentController::class, 'index']);
Route::get('/employees', [EmployeeController::class, 'index']);
Route::get('/employees/{employee}', [EmployeeController::class, 'show']);

// Protected routes — require a valid Sanctum token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::match(['put', 'patch'], '/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});