<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123', // User model already hashes this
        ]);

        $it = Department::create(['name' => 'Information Technology', 'code' => 'IT']);
        $hr = Department::create(['name' => 'Human Resources', 'code' => 'HR']);

        $it->employees()->create([
            'employee_number' => 'EMP-0001', 'first_name' => 'Juan', 'last_name' => 'Dela Cruz',
            'email' => 'juan@example.com', 'position' => 'Developer', 'employment_status' => 'Active',
        ]);
        $it->employees()->create([
            'employee_number' => 'EMP-0002', 'first_name' => 'Pedro', 'last_name' => 'Reyes',
            'email' => 'pedro@example.com', 'position' => 'QA Tester', 'employment_status' => 'Active',
        ]);
        $hr->employees()->create([
            'employee_number' => 'EMP-0003', 'first_name' => 'Maria', 'last_name' => 'Santos',
            'email' => 'maria@example.com', 'position' => 'HR Officer', 'employment_status' => 'Inactive',
        ]);
    }
}