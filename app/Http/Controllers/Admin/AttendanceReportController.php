<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    /**
     * Display the attendance report.
     */
    public function index(Request $request)
    {
        $query = Attendance::with('employee');

        // Date filter
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->start_date) 
            : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->end_date) 
            : Carbon::now();

        $query->dateRange($startDate, $endDate);

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(20)
            ->withQueryString();

        $employees = Employee::active()->orderBy('name')->get();

        // Summary statistics
        $summary = [
            'total' => $attendances->total(),
            'on_time' => Attendance::dateRange($startDate, $endDate)->where('status', 'on_time')->count(),
            'late' => Attendance::dateRange($startDate, $endDate)->where('status', 'late')->count(),
            'early' => Attendance::dateRange($startDate, $endDate)->where('status', 'early')->count(),
        ];

        return view('admin.reports.index', compact(
            'attendances', 
            'employees', 
            'startDate', 
            'endDate',
            'summary'
        ));
    }

    /**
     * Export attendance report.
     */
    public function export(Request $request)
    {
        // TODO: Implement export to Excel/PDF
        return redirect()->back()->with('info', 'Fitur export akan segera tersedia.');
    }
}
