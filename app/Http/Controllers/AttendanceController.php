<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display the attendance page with camera.
     */
    public function index()
    {
        return view('attendance.index');
    }

    /**
     * Get all employee face descriptors for matching.
     */
    public function getEmployeeDescriptors()
    {
        $employees = Employee::active()
            ->withFace()
            ->select('id', 'employee_number', 'name', 'face_descriptors', 'face_photo_path')
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'employee_number' => $employee->employee_number,
                    'name' => $employee->name,
                    'face_descriptors' => $employee->face_descriptors,
                    'face_photo' => $employee->face_photo_path 
                        ? asset($employee->face_photo_path) 
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'employees' => $employees,
        ]);
    }

    /**
     * Verify face and record attendance.
     */
    public function verifyFace(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:clock_in,clock_out,break_start,break_end',
            'face_match_score' => 'required|numeric|min:0|max:1',
            'photo' => 'nullable|string', // Base64 image
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $now = Carbon::now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        // Check face match threshold (0.6 = 60% minimum similarity)
        if ($validated['face_match_score'] < 0.6) {
            return response()->json([
                'success' => false,
                'message' => 'Wajah tidak cocok. Silakan coba lagi.',
            ], 400);
        }

        // Check for duplicate attendance
        $existingAttendance = Attendance::where('employee_id', $employee->id)
            ->where('date', $today)
            ->where('type', $validated['type'])
            ->first();

        if ($existingAttendance) {
            $typeLabel = Attendance::TYPES[$validated['type']];
            return response()->json([
                'success' => false,
                'message' => "Anda sudah melakukan absen {$typeLabel} hari ini.",
            ], 400);
        }

        // Get employee schedule to validate time
        $schedule = $employee->getTodaySchedule();

        if ($schedule && $schedule->workSchedule) {
            $workSchedule = $schedule->workSchedule;
            
            // Validation: Cannot clock in if shift has ended
            if ($validated['type'] === 'clock_in') {
                $clockOutTime = Carbon::parse($workSchedule->clock_out);
                // Handle cross-day shifts, only validate if standard day shift
                if ($clockOutTime->gt(Carbon::parse($workSchedule->clock_in))) {
                    if ($now->gt($clockOutTime)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal! Jam kerja telah berakhir (' . $clockOutTime->format('H:i') . ').',
                        ], 400);
                    }
                }
            }
        }

        // Determine status based on schedule
        $status = $this->determineAttendanceStatus($employee, $validated['type'], $currentTime);

        // Save photo if provided
        $photoPath = null;
        if (!empty($validated['photo'])) {
            $photoPath = $this->saveAttendancePhoto($validated['photo'], $employee->id, $validated['type']);
        }

        // Create attendance record
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'date' => $today,
            'type' => $validated['type'],
            'time' => $currentTime,
            'face_match_score' => $validated['face_match_score'],
            'photo_path' => $photoPath,
            'status' => $status,
        ]);

        $typeLabel = Attendance::TYPES[$validated['type']];
        $statusLabel = Attendance::STATUSES[$status];

        // Customize message based on status
        $message = "Absen {$typeLabel} berhasil!";
        if ($status === 'late') {
            $tolerance = $workSchedule->late_tolerance ?? 0;
            $message .= " Status: Terlambat (Toleransi {$tolerance} menit).";
        } elseif ($status === 'early') {
            $message .= " Status: Pulang Cepat.";
        } elseif ($status === 'no_schedule') {
            $message .= " Status: Tidak Ada Jadwal.";
        } else {
            $message .= " Status: Tepat Waktu.";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'status_color' => Attendance::STATUS_COLORS[$status] ?? 'success',
            'attendance' => [
                'id' => $attendance->id,
                'employee_name' => $employee->name,
                'type' => $typeLabel,
                'time' => $now->format('H:i'),
                'status' => $status,
                'status_label' => $statusLabel,
                'status_color' => Attendance::STATUS_COLORS[$status] ?? 'success',
            ],
        ]);
    }

    /**
     * Determine attendance status based on schedule.
     */
    private function determineAttendanceStatus(Employee $employee, string $type, string $time): string
    {
        $schedule = $employee->getTodaySchedule();
        
        if (!$schedule || !$schedule->workSchedule) {
            return 'no_schedule';
        }

        $workSchedule = $schedule->workSchedule;
        $currentTime = Carbon::parse($time);

        switch ($type) {
            case 'clock_in':
                if ($workSchedule->isLateForClockIn($time)) {
                    return 'late';
                }
                return 'on_time';

            case 'clock_out':
                if ($workSchedule->isEarlyForClockOut($time)) {
                    return 'early';
                }
                return 'on_time';

            default:
                return 'on_time';
        }
    }

    /**
     * Save attendance photo.
     */
    private function saveAttendancePhoto(string $base64Image, int $employeeId, string $type): ?string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $extension = strtolower($matches[1]);
            $imageData = base64_decode($imageData);

            $filename = 'attendances/' . $employeeId . '_' . $type . '_' . time() . '.' . $extension;
            \Storage::disk('public_uploads')->put($filename, $imageData);

            return $filename;
        }

        return null;
    }

    /**
     * Get employee's today attendance status.
     */
    public function getTodayStatus(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $attendances = $employee->getTodayAttendances();

        $status = [
            'clock_in' => null,
            'clock_out' => null,
            'break_start' => null,
            'break_end' => null,
        ];

        foreach ($attendances as $attendance) {
            $status[$attendance->type] = [
                'time' => Carbon::parse($attendance->time)->format('H:i'),
                'status' => $attendance->status,
                'status_label' => $attendance->status_label,
            ];
        }

        return response()->json([
            'success' => true,
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'today_status' => $status,
        ]);
    }
}
