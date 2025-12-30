<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Position;
use App\Models\WorkSchedule;
use App\Models\EmployeeSchedule;
use App\Models\Attendance;
use Carbon\Carbon;

class AbsenteeSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Position with Clear Absent Fee
        $position = Position::where('name', 'Staff')->first();
        if ($position) {
            $position->update([
                'absent_fee' => 100000, // Rp 100.000 per day
                'meal_allowance' => 50000,
            ]);
        }

        // 2. Create "Dodi Alpha"
        $emp = Employee::firstOrCreate(
            ['employee_number' => 'TEST001'],
            [
                'name' => 'Dodi Alpha (Test Bolos)',
                'email' => 'dodi.alpha@example.com',
                'department' => 'Operations',
                'position' => 'Staff',
                'is_active' => true
            ]
        );

        // 3. Assign Schedule (Mon-Fri)
        $schedule = WorkSchedule::firstOrCreate(
            ['name' => 'Regular Shift'],
            ['clock_in' => '08:00:00', 'clock_out' => '17:00:00']
        );

        for ($day = 1; $day <= 5; $day++) {
            EmployeeSchedule::updateOrCreate(
                ['employee_id' => $emp->id, 'day_of_week' => $day],
                ['work_schedule_id' => $schedule->id, 'is_active' => true]
            );
        }

        // 4. Generate Attendance (Skip 3 Days)
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::now();
        
        // Determine 3 random dates (workdays) to be ABSENT
        $workdays = [];
        for ($d = $startOfMonth->copy(); $d->lte($today); $d->addDay()) {
            if (!$d->isWeekend()) {
                $workdays[] = $d->copy();
            }
        }
        
        $absentDates = [];
        if (count($workdays) >= 3) {
            $absentKeys = array_rand($workdays, 3);
            foreach ($absentKeys as $k) {
                $absentDates[] = $workdays[$k]->toDateString();
            }
        }

        $this->command->info("Simulating Absences for 'Dodi Alpha' on: " . implode(', ', $absentDates));

        // Create Attendance for all OTHER days
        foreach ($workdays as $date) {
            if (in_array($date->toDateString(), $absentDates)) {
                // SKIP -> This creates an Absence
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'employee_id' => $emp->id,
                    'date' => $date->toDateString(),
                ],
                [
                    'time' => '07:55:00', // On Time
                    'type' => 'clock_in',
                    'status' => 'on_time',
                    'face_match_score' => 0.98,
                ]
            );
        }
        
        $this->command->info("Data created! 'Dodi Alpha' has 3 days absent.");
    }
}
