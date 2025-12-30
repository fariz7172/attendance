<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use App\Models\WorkSchedule;
use App\Models\EmployeeSchedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Positions with Salary
        $positions = [
            'Manager' => [
                'basic_salary' => 10000000,
                'meal_allowance' => 50000,
                'late_fee_per_minute' => 5000
            ],
            'Supervisor' => [
                'basic_salary' => 7000000,
                'meal_allowance' => 40000,
                'late_fee_per_minute' => 2000
            ],
            'Staff' => [
                'basic_salary' => 4500000,
                'meal_allowance' => 25000,
                'late_fee_per_minute' => 1000
            ],
            'Admin' => [
                'basic_salary' => 4000000,
                'meal_allowance' => 25000,
                'late_fee_per_minute' => 1000
            ],
            'Intern' => [
                'basic_salary' => 1500000,
                'meal_allowance' => 15000,
                'late_fee_per_minute' => 500
            ]
        ];

        foreach ($positions as $name => $salary) {
            Position::firstOrCreate(
                ['name' => $name],
                $salary
            );
        }

        // 2. Ensure Departments exist
        $depts = ['IT', 'HR', 'Finance', 'Operations', 'Marketing'];
        foreach ($depts as $d) {
            Department::firstOrCreate(['name' => $d]);
        }

        // 3. Ensure Work Schedule exists (08:00 - 17:00)
        $schedule = WorkSchedule::firstOrCreate(
            ['name' => 'Regular Shift'],
            [
                'clock_in' => '08:00:00',
                'clock_out' => '17:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'late_tolerance' => 0, // Strict for testing
                'is_active' => true
            ]
        );

        // 4. Create 5 Employees
        $employeesData = [
            ['name' => 'Budi Manager', 'pos' => 'Manager', 'dept' => 'IT'],
            ['name' => 'Siti Supervisor', 'pos' => 'Supervisor', 'dept' => 'HR'],
            ['name' => 'Andi Staff', 'pos' => 'Staff', 'dept' => 'Finance'],
            ['name' => 'Dewi Admin', 'pos' => 'Admin', 'dept' => 'Operations'],
            ['name' => 'Riko Intern', 'pos' => 'Intern', 'dept' => 'IT'],
        ];

        foreach ($employeesData as $index => $data) {
            $emp = Employee::firstOrCreate(
                ['employee_number' => 'EMP00' . ($index + 1)],
                [
                    'name' => $data['name'],
                    'email' => strtolower(str_replace(' ', '.', $data['name'])) . '@example.com',
                    'department' => $data['dept'],
                    'position' => $data['pos'],
                    'is_active' => true
                ]
            );

            // Assign Schedule (Mon-Fri)
            for ($day = 1; $day <= 5; $day++) {
                EmployeeSchedule::firstOrCreate(
                    [
                        'employee_id' => $emp->id,
                        'day_of_week' => $day
                    ],
                    [
                        'work_schedule_id' => $schedule->id,
                        'is_active' => true
                    ]
                );
            }

            // 5. Generate Attendance for Current Month (up to today)
            $startOfMonth = Carbon::now()->startOfMonth();
            $today = Carbon::now();

            for ($date = $startOfMonth->copy(); $date->lte($today); $date->addDay()) {
                // Skip weekends
                if ($date->isWeekend()) continue;

                // Randomize scenario: 80% On Time, 20% Late
                $isLate = rand(1, 10) > 8;
                
                if ($isLate) {
                    // Late between 5 to 60 minutes
                    $lateMinutes = rand(5, 60);
                    $clockInTime = Carbon::parse('08:00:00')->addMinutes($lateMinutes);
                    $status = 'late';
                } else {
                    // On Time (07:45 - 07:55)
                    $clockInTime = Carbon::parse('08:00:00')->subMinutes(rand(5, 15));
                    $status = 'on_time';
                }

                Attendance::updateOrCreate(
                    [
                        'employee_id' => $emp->id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'time' => $clockInTime->format('H:i:s'),
                        'type' => 'clock_in', // Added required type
                        'status' => $status,
                        'face_match_score' => 0.95, // Dummy score
                        'photo_path' => null 
                    ]
                );
            }
        }
        
        $this->command->info('Seeder executed successfully!');
    }
}
