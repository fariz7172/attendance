@extends('layouts.admin')

@section('title', 'Detail Karyawan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.employees.index') }}">Karyawan</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>{{ $employee->name }}</span>
@endsection

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Detail Karyawan</h1>
            <p class="page-description">Informasi lengkap dan riwayat kehadiran</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.employees.register-face', $employee) }}" class="btn btn-primary">
                <i class="fas fa-camera"></i>
                {{ $employee->hasFaceRegistered() ? 'Update Wajah' : 'Daftarkan Wajah' }}
            </a>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-secondary">
                <i class="fas fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <!-- Profile Card -->
        <div>
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 32px;">
                    <div class="avatar" style="width: 120px; height: 120px; margin: 0 auto 20px; font-size: 48px;">
                        @if($employee->face_photo_path)
                            <img src="{{ asset($employee->face_photo_path) }}" alt="{{ $employee->name }}">
                        @else
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        @endif
                    </div>
                    <h2 style="margin-bottom: 4px;">{{ $employee->name }}</h2>
                    <p style="color: var(--color-text-muted); margin-bottom: 16px;">
                        {{ $employee->position ?? 'Karyawan' }} - {{ $employee->department ?? 'Umum' }}
                    </p>

                    <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; margin-bottom: 24px;">
                        @if($employee->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif

                        @if($employee->hasFaceRegistered())
                            <span class="badge badge-info">Wajah Terdaftar</span>
                        @else
                            <span class="badge badge-warning">Wajah Belum Terdaftar</span>
                        @endif
                    </div>

                    <div style="text-align: left; background: var(--color-bg-primary); border-radius: var(--border-radius); padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <i class="fas fa-id-card" style="color: var(--color-primary); width: 20px;"></i>
                            <div>
                                <div style="font-size: 12px; color: var(--color-text-muted);">No. Karyawan</div>
                                <div style="font-weight: 500;">{{ $employee->employee_number }}</div>
                            </div>
                        </div>
                        @if($employee->email)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <i class="fas fa-envelope" style="color: var(--color-primary); width: 20px;"></i>
                            <div>
                                <div style="font-size: 12px; color: var(--color-text-muted);">Email</div>
                                <div style="font-weight: 500;">{{ $employee->email }}</div>
                            </div>
                        </div>
                        @endif
                        @if($employee->phone)
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-phone" style="color: var(--color-primary); width: 20px;"></i>
                            <div>
                                <div style="font-size: 12px; color: var(--color-text-muted);">Telepon</div>
                                <div style="font-weight: 500;">{{ $employee->phone }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt" style="color: var(--color-primary); margin-right: 8px;"></i>
                        Jadwal Kerja
                    </h3>
                </div>
                <div class="card-body">
                    @if($employee->schedules->count() > 0)
                        @php
                            $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                        @endphp
                        @foreach($dayNames as $dayNum => $dayName)
                            @php
                                $schedule = $employee->schedules->where('day_of_week', $dayNum)->first();
                            @endphp
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--color-bg-tertiary);">
                                <span style="font-weight: 500;">{{ $dayName }}</span>
                                @if($schedule && $schedule->workSchedule)
                                    <span style="color: var(--color-primary);">
                                        {{ \Carbon\Carbon::parse($schedule->workSchedule->clock_in)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->workSchedule->clock_out)->format('H:i') }}
                                    </span>
                                @else
                                    <span style="color: var(--color-text-muted);">Libur</span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p style="color: var(--color-text-muted); text-align: center;">Belum ada jadwal</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history" style="color: var(--color-primary); margin-right: 8px;"></i>
                        Riwayat Kehadiran Terbaru
                    </h3>
                    <a href="{{ route('admin.reports.index', ['employee_id' => $employee->id]) }}" class="btn btn-secondary btn-sm">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($employee->attendances->count() > 0)
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employee->attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d M Y') }}</td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $attendance->type_label }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ \Carbon\Carbon::parse($attendance->time)->format('H:i') }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $attendance->status_color }}">
                                                    {{ $attendance->status_label }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h3>Belum Ada Riwayat</h3>
                            <p>Karyawan ini belum memiliki riwayat kehadiran.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media (max-width: 1024px) {
        .page-content > div:last-child {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
