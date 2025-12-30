@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-description">Selamat datang! Berikut adalah ringkasan data kehadiran hari ini.</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $totalEmployees }}</div>
            <div class="stat-label">Total Karyawan</div>
        </div>

        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-value">{{ $todayClockIns }}</div>
            <div class="stat-label">Hadir Hari Ini</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $todayLate }}</div>
            <div class="stat-label">Terlambat</div>
        </div>

        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value">{{ $attendanceRate }}%</div>
            <div class="stat-label">Tingkat Kehadiran Bulan Ini</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- Recent Attendance -->
        <div class="card" style="grid-column: span 2;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history" style="color: var(--color-primary); margin-right: 8px;"></i>
                    Kehadiran Terbaru Hari Ini
                </h3>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-sm">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentAttendances->count() > 0)
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Tipe</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Match Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div class="avatar">
                                                    @if($attendance->employee->face_photo_path)
                                                        <img src="{{ asset($attendance->employee->face_photo_path) }}" alt="">
                                                    @else
                                                        {{ strtoupper(substr($attendance->employee->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <div style="font-weight: 500;">{{ $attendance->employee->name }}</div>
                                                    <div style="font-size: 12px; color: var(--color-text-muted);">
                                                        {{ $attendance->employee->employee_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ $attendance->type_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $attendance->status_color }}">
                                                {{ $attendance->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->face_match_score)
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <div style="width: 60px; height: 6px; background: var(--color-bg-tertiary); border-radius: 3px; overflow: hidden;">
                                                        <div style="width: {{ $attendance->face_match_score * 100 }}%; height: 100%; background: linear-gradient(90deg, var(--color-primary), var(--color-success)); border-radius: 3px;"></div>
                                                    </div>
                                                    <span style="font-size: 12px; color: var(--color-text-muted);">
                                                        {{ number_format($attendance->face_match_score * 100, 0) }}%
                                                    </span>
                                                </div>
                                            @else
                                                <span style="color: var(--color-text-muted);">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Belum Ada Kehadiran</h3>
                        <p>Belum ada data kehadiran hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

  
@endsection
