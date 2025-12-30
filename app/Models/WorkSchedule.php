<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'late_tolerance',
        'is_active',
    ];

    protected $casts = [
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
        'late_tolerance' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get all employee schedules using this work schedule.
     */
    public function employeeSchedules(): HasMany
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    /**
     * Check if given time is within clock in tolerance.
     */
    public function isOnTimeForClockIn(string $time): bool
    {
        $clockInTime = Carbon::parse($this->clock_in);
        $currentTime = Carbon::parse($time);
        $toleranceTime = $clockInTime->copy()->addMinutes($this->late_tolerance);

        return $currentTime->lte($toleranceTime);
    }

    /**
     * Check if given time is late for clock in.
     */
    public function isLateForClockIn(string $time): bool
    {
        $clockInTime = Carbon::parse($this->clock_in);
        $currentTime = Carbon::parse($time);
        $toleranceTime = $clockInTime->copy()->addMinutes($this->late_tolerance);

        return $currentTime->gt($toleranceTime);
    }

    /**
     * Check if given time is early for clock out.
     */
    public function isEarlyForClockOut(string $time): bool
    {
        $clockOutTime = Carbon::parse($this->clock_out);
        $currentTime = Carbon::parse($time);

        return $currentTime->lt($clockOutTime);
    }

    /**
     * Get formatted schedule string.
     */
    public function getFormattedScheduleAttribute(): string
    {
        $schedule = Carbon::parse($this->clock_in)->format('H:i') . ' - ' . Carbon::parse($this->clock_out)->format('H:i');
        
        if ($this->break_start && $this->break_end) {
            $schedule .= ' (Istirahat: ' . Carbon::parse($this->break_start)->format('H:i') . ' - ' . Carbon::parse($this->break_end)->format('H:i') . ')';
        }

        return $schedule;
    }

    /**
     * Scope for active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
