<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'name',
        'email',
        'phone',
        'department',
        'position',
        'face_descriptors',
        'face_photo_path',
        'is_active',
    ];

    protected $casts = [
        'face_descriptors' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all attendances for the employee.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all schedules for the employee.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    /**
     * Check if employee has face registered.
     */
    public function hasFaceRegistered(): bool
    {
        return !empty($this->face_descriptors);
    }

    /**
     * Get today's schedule for the employee.
     */
    public function getTodaySchedule(): ?EmployeeSchedule
    {
        $dayOfWeek = now()->dayOfWeekIso; // 1 (Monday) to 7 (Sunday)
        
        return $this->schedules()
            ->with('workSchedule')
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get today's attendance records.
     */
    public function getTodayAttendances()
    {
        return $this->attendances()
            ->where('date', now()->toDateString())
            ->orderBy('time')
            ->get();
    }

    /**
     * Check if already clocked in today.
     */
    public function hasClockInToday(): bool
    {
        return $this->attendances()
            ->where('date', now()->toDateString())
            ->where('type', 'clock_in')
            ->exists();
    }

    /**
     * Check if already clocked out today.
     */
    public function hasClockOutToday(): bool
    {
        return $this->attendances()
            ->where('date', now()->toDateString())
            ->where('type', 'clock_out')
            ->exists();
    }

    /**
     * Scope for active employees.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for employees with face registered.
     */
    public function scopeWithFace($query)
    {
        return $query->whereNotNull('face_descriptors');
    }
}
