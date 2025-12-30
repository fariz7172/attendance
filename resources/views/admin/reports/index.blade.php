@extends('layouts.admin')

@section('title', 'Laporan Kehadiran')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Laporan Kehadiran</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Laporan Kehadiran</h1>
        <p class="page-description">Lihat dan filter data kehadiran karyawan</p>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid" style="margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-value">{{ number_format($summary['total']) }}</div>
            <div class="stat-label">Total Record</div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">
                <i class="fas fa-check"></i>
            </div>
            <div class="stat-value">{{ number_format($summary['on_time']) }}</div>
            <div class="stat-label">Tepat Waktu</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ number_format($summary['late']) }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="stat-value">{{ number_format($summary['early']) }}</div>
            <div class="stat-label">Pulang Awal</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body">
            <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="margin: 0; min-width: 150px;">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="form-group" style="margin: 0; min-width: 150px;">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="form-group" style="margin: 0; min-width: 180px;">
                    <label class="form-label">Karyawan</label>
                    <select name="employee_id" class="form-control">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin: 0; min-width: 150px;">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-control">
                        <option value="">Semua Tipe</option>
                        @foreach(\App\Models\Attendance::TYPES as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin: 0; min-width: 150px;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Attendance::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                @if(request()->hasAny(['start_date', 'end_date', 'employee_id', 'type', 'status']))
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table" style="color: var(--color-primary); margin-right: 8px;"></i>
                Data Kehadiran
            </h3>
            <span style="color: var(--color-text-muted); font-size: 14px;">
                {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
            </span>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($attendances->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Tipe</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Match Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div>
                                            <div style="font-weight: 500;">{{ $attendance->date->format('d M Y') }}</div>
                                            <div style="font-size: 12px; color: var(--color-text-muted);">
                                                {{ $attendance->date->translatedFormat('l') }}
                                            </div>
                                        </div>
                                    </td>
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
                                        <span class="badge badge-secondary">{{ $attendance->type_label }}</span>
                                    </td>
                                    <td>
                                        <strong style="font-size: 16px;">{{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status_color }}">
                                            {{ $attendance->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($attendance->face_match_score)
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <div style="width: 50px; height: 6px; background: var(--color-bg-tertiary); border-radius: 3px; overflow: hidden;">
                                                    <div style="width: {{ $attendance->face_match_score * 100 }}%; height: 100%; background: linear-gradient(90deg, var(--color-primary), var(--color-success));"></div>
                                                </div>
                                                <span style="font-size: 12px;">{{ number_format($attendance->face_match_score * 100, 0) }}%</span>
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

                @if($attendances->hasPages())
                    <div style="padding: 16px;">
                        {{ $attendances->links('vendor.pagination.modern') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>Tidak Ada Data</h3>
                    <p>Tidak ada data kehadiran yang sesuai dengan filter.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
