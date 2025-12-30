<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $payrolls = Payroll::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->latest()
            ->paginate(10);

        return view('admin.payrolls.index', compact('payrolls', 'month', 'year'));
    }

    /**
     * Show the form for creating a new resource (Generate Payroll).
     */
    public function create()
    {
        return view('admin.payrolls.create');
    }

    /**
     * Store a newly created resource in storage (Generate Process).
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Check if already generated
        $exists = Payroll::where('month', $month)->where('year', $year)->exists();
        if ($exists && !$request->has('regenerate')) {
            return back()->with('warning', 'Payroll untuk periode ini sudah ada. Hapus terlebih dahulu atau gunakan fitur regenerasi (Coming Soon).');
        }

        // Get Employees with Position
        $employees = Employee::whereNotNull('position')->get();
        $count = 0;

        DB::beginTransaction();
        try {
            foreach ($employees as $employee) {
                // Get Position Data (Salary Config)
                // Note: We match by name since relation is loose (string based)
                $position = Position::where('name', $employee->position)->first();
                
                if (!$position) continue; // Skip if position master not found

                // Calculate Attendance
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

                $attendances = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->get();

                // 1. Calculate Attendance Stats
                $presentDays = 0;
                $absentDays = 0;
                
                // Iterate through each day of the month to check schedule vs attendance
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    // Check if employee has schedule for this day
                    $hasSchedule = $employee->schedules()
                        ->where('day_of_week', $currentDate->dayOfWeekIso)
                        ->where('is_active', true)
                        ->exists();

                    if ($hasSchedule) {
                        // Check if attended
                        $attended = $attendances->contains(function ($att) use ($currentDate) {
                            return $att->date->isSameDay($currentDate);
                        });

                        if ($attended) {
                            $presentDays++;
                        } else {
                            // If today is future, don't count as absent yet? 
                            // Or assuming "Generate" is done at end of month.
                            if ($currentDate->lte(now())) {
                                $absentDays++;
                            }
                        }
                    }
                    $currentDate->addDay();
                }

                $totalMealAllowance = $presentDays * $position->meal_allowance;
                $totalAbsentDeduction = $absentDays * $position->absent_fee;

                // 2. Final Calculation
                $basicSalary = $position->basic_salary;
                $gross = $basicSalary + $totalMealAllowance;
                $net = $gross - $totalAbsentDeduction;

                // 3. Save
                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'month' => $month,
                        'year' => $year,
                    ],
                    [
                        'basic_salary' => $basicSalary,
                        'meal_allowance' => $position->meal_allowance,
                        'absent_fee' => $position->absent_fee,
                        'total_allowance' => $totalMealAllowance,
                        'total_deduction' => $totalAbsentDeduction,
                        'net_salary' => $net,
                        'attendance_summary' => [
                            'present_days' => $presentDays,
                            'absent_days' => $absentDays,
                            'total_work_days' => $presentDays + $absentDays,
                        ],
                        'status' => 'draft',
                    ]
                );
                
                $count++;
            }
            
            DB::commit();

            return redirect()->route('admin.payrolls.index', ['month' => $month, 'year' => $year])
                ->with('success', "Payroll berhasil digenerate untuk $count karyawan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource (Payslip).
     */
    public function show(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('admin.payrolls.show', compact('payroll'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Data payroll berhasil dihapus.');
    }
}
