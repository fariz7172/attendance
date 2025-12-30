<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'type',
        'time',
        'face_match_score',
        'photo_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'face_match_score' => 'decimal:4',
    ];

    /**
     * Attendance types.
     */
    public const TYPES = [
        'clock_in' => 'Masuk',
        'clock_out' => 'Pulang',
        'break_start' => 'Mulai Istirahat',
        'break_end' => 'Selesai Istirahat',
    ];

    /**
     * Status labels.
     */
    public const STATUSES = [
        'on_time' => 'Tepat Waktu',
        'late' => 'Terlambat',
        'early' => 'Pulang Awal',
        'absent' => 'Tidak Hadir',
        'no_schedule' => 'Tidak Ada Jadwal',
    ];

    /**
     * Status colors for UI.
     */
    public const STATUS_COLORS = [
        'on_time' => 'success',
        'late' => 'danger',
        'early' => 'info',
        'absent' => 'danger',
        'no_schedule' => 'secondary',
    ];

    /**
     * Get the employee that owns this attendance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Get the status color.
     */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    /**
     * Scope for today's attendances.
     */
    public function scopeToday($query)
    {
        return $query->where('date', now()->toDateString());
    }

    /**
     * Scope for this month's attendances.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }

    /**
     * Scope for specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
