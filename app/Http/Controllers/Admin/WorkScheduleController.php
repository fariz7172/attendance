<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of work schedules.
     */
    public function index()
    {
        $schedules = WorkSchedule::orderBy('clock_in')->get();
        return view('admin.work-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new work schedule.
     */
    public function create()
    {
        return view('admin.work-schedules.create');
    }

    /**
     * Store a newly created work schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'required|date_format:H:i',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'late_tolerance' => 'required|integer|min:0|max:60',
        ]);

        WorkSchedule::create($validated);

        return redirect()
            ->route('admin.work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified work schedule.
     */
    public function edit(WorkSchedule $workSchedule)
    {
        return view('admin.work-schedules.edit', compact('workSchedule'));
    }

    /**
     * Update the specified work schedule.
     */
    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'required|date_format:H:i',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'late_tolerance' => 'required|integer|min:0|max:60',
            'is_active' => 'boolean',
        ]);

        $workSchedule->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified work schedule.
     */
    public function destroy(WorkSchedule $workSchedule)
    {
        // Check if schedule is being used
        if ($workSchedule->employeeSchedules()->exists()) {
            return redirect()
                ->route('admin.work-schedules.index')
                ->with('error', 'Jadwal kerja tidak dapat dihapus karena sedang digunakan.');
        }

        $workSchedule->delete();

        return redirect()
            ->route('admin.work-schedules.index')
            ->with('success', 'Jadwal kerja berhasil dihapus.');
    }
}
