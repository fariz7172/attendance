<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkSchedule;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schedules = [
            [
                'name' => 'Shift Pagi',
                'clock_in' => '08:00',
                'clock_out' => '17:00',
                'break_start' => '12:00',
                'break_end' => '13:00',
                'late_tolerance' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Shift Siang',
                'clock_in' => '14:00',
                'clock_out' => '22:00',
                'break_start' => '18:00',
                'break_end' => '19:00',
                'late_tolerance' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Shift Malam',
                'clock_in' => '22:00',
                'clock_out' => '06:00',
                'break_start' => '02:00',
                'break_end' => '03:00',
                'late_tolerance' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $schedule) {
            WorkSchedule::create($schedule);
        }
    }
}
