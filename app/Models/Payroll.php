<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'meal_allowance',
        'absent_fee',
        'total_allowance',
        'total_deduction',
        'net_salary',
        'attendance_summary',
        'status',
    ];

    protected $casts = [
        'attendance_summary' => 'array',
        'basic_salary' => 'decimal:2',
        'meal_allowance' => 'decimal:2',
        'absent_fee' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
