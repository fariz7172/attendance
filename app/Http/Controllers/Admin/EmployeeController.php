<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\WorkSchedule;
use App\Models\EmployeeSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by face status
        if ($request->filled('face_status')) {
            if ($request->face_status === 'registered') {
                $query->withFace();
            } else {
                $query->whereNull('face_descriptors');
            }
        }

        $employees = $query->orderBy('name')->paginate(10);
        $departments = Employee::distinct()->pluck('department')->filter();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $workSchedules = WorkSchedule::active()->get();
        $departments = Department::all();
        $positions = Position::all();
        return view('admin.employees.create', compact('workSchedules', 'departments', 'positions'));
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'schedules' => 'nullable|array',
            'schedules.*' => 'nullable|exists:work_schedules,id',
        ]);

        $employee = Employee::create([
            'employee_number' => $validated['employee_number'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'position' => $validated['position'] ?? null,
            'is_active' => true,
        ]);

        // Assign schedules for weekdays
        if (!empty($validated['schedules'])) {
            foreach ($validated['schedules'] as $dayOfWeek => $scheduleId) {
                if ($scheduleId) {
                    EmployeeSchedule::create([
                        'employee_id' => $employee->id,
                        'work_schedule_id' => $scheduleId,
                        'day_of_week' => $dayOfWeek,
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.employees.register-face', $employee)
            ->with('success', 'Karyawan berhasil ditambahkan. Silakan daftarkan wajah.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load(['schedules.workSchedule', 'attendances' => function($query) {
            $query->orderBy('date', 'desc')->orderBy('time', 'desc')->take(20);
        }]);

        return view('admin.employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $workSchedules = WorkSchedule::active()->get();
        $employee->load('schedules');
        
        // Create schedule map for form
        $employeeSchedules = $employee->schedules->keyBy('day_of_week');

        $departments = Department::all();
        $positions = Position::all();

        return view('admin.employees.edit', compact('employee', 'workSchedules', 'employeeSchedules', 'departments', 'positions'));
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number,' . $employee->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'schedules' => 'nullable|array',
            'schedules.*' => 'nullable|exists:work_schedules,id',
        ]);

        $employee->update([
            'employee_number' => $validated['employee_number'],
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'position' => $validated['position'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Update schedules
        if (isset($validated['schedules'])) {
            // Remove old schedules
            $employee->schedules()->delete();
            
            // Add new schedules
            foreach ($validated['schedules'] as $dayOfWeek => $scheduleId) {
                if ($scheduleId) {
                    EmployeeSchedule::create([
                        'employee_id' => $employee->id,
                        'work_schedule_id' => $scheduleId,
                        'day_of_week' => $dayOfWeek,
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(Employee $employee)
    {
        // Delete face photo if exists
        if ($employee->face_photo_path) {
            Storage::disk('public_uploads')->delete($employee->face_photo_path);
        }

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Show the face registration page.
     */
    public function registerFace(Employee $employee)
    {
        return view('admin.employees.register-face', compact('employee'));
    }

    /**
     * Store face descriptor via AJAX.
     */
    public function storeFaceDescriptor(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'face_descriptors' => 'required|array',
            'face_photo' => 'nullable|string', // Base64 image
        ]);

        // Save face photo if provided
        if (!empty($validated['face_photo'])) {
            // Decode base64 image
            $imageData = $validated['face_photo'];
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);

                $filename = 'faces/' . $employee->id . '_' . time() . '.' . $type;
                Storage::disk('public_uploads')->put($filename, $imageData);

                // Delete old photo
                if ($employee->face_photo_path) {
                    Storage::disk('public_uploads')->delete($employee->face_photo_path);
                }

                $employee->face_photo_path = $filename;
            }
        }

        $employee->face_descriptors = $validated['face_descriptors'];
        $employee->save();

        return response()->json([
            'success' => true,
            'message' => 'Wajah berhasil didaftarkan.',
        ]);
    }
}
