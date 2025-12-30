<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();
        
        // Statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::active()->count();
        $employeesWithFace = Employee::withFace()->count();
        
        // Today's attendance stats
        $todayClockIns = Attendance::today()->ofType('clock_in')->count();
        $todayLate = Attendance::today()->ofType('clock_in')->where('status', 'late')->count();
        $todayOnTime = Attendance::today()->ofType('clock_in')->where('status', 'on_time')->count();
        
        // Recent attendances
        $recentAttendances = Attendance::with('employee')
            ->today()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Attendance rate this month
        $workDays = $this->getWorkDaysThisMonth();
        $expectedAttendances = $activeEmployees * $workDays;
        $actualAttendances = Attendance::thisMonth()
            ->ofType('clock_in')
            ->distinct('employee_id', 'date')
            ->count();
        $attendanceRate = $expectedAttendances > 0 
            ? round(($actualAttendances / $expectedAttendances) * 100, 1) 
            : 0;

        return view('admin.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'employeesWithFace',
            'todayClockIns',
            'todayLate',
            'todayOnTime',
            'recentAttendances',
            'attendanceRate'
        ));
    }

    /**
     * Get number of work days this month (excluding weekends).
     */
    private function getWorkDaysThisMonth(): int
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now();
        $workDays = 0;

        while ($start->lte($end)) {
            if (!$start->isWeekend()) {
                $workDays++;
            }
            $start->addDay();
        }

        return $workDays;
    }
}
