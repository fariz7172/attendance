@extends('layouts.admin')

@section('title', 'Edit Karyawan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.employees.index') }}">Karyawan</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Edit</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Karyawan</h1>
        <p class="page-description">Perbarui data karyawan {{ $employee->name }}</p>
    </div>

    <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <!-- Main Form -->
            <div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Informasi Karyawan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">No. Karyawan <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="employee_number" class="form-control @error('employee_number') is-invalid @enderror" 
                                       value="{{ old('employee_number', $employee->employee_number) }}" required>
                                @error('employee_number')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $employee->name) }}" required>
                                @error('name')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $employee->email) }}">
                                @error('email')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $employee->phone) }}">
                                @error('phone')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Departemen</label>
                                <select name="department" class="form-control @error('department') is-invalid @enderror">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->name }}" {{ old('department', $employee->department) == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Posisi/Jabatan</label>
                                <select name="position" class="form-control @error('position') is-invalid @enderror">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->name }}" {{ old('position', $employee->position) == $pos->name ? 'selected' : '' }}>{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label class="form-label">Status</label>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $employee->is_active) ? 'checked' : '' }}
                                           style="width: 18px; height: 18px; cursor: pointer;">
                                    <span>Karyawan Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Assignment -->
                <div class="card" style="margin-top: 24px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt" style="color: var(--color-primary); margin-right: 8px;"></i>
                            Jadwal Kerja
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($workSchedules->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                                @php
                                    $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                                @endphp
                                @foreach($days as $dayNum => $dayName)
                                    <div class="form-group" style="margin: 0;">
                                        <label class="form-label">{{ $dayName }}</label>
                                        <select name="schedules[{{ $dayNum }}]" class="form-control">
                                            <option value="">-- Libur --</option>
                                            @foreach($workSchedules as $schedule)
                                                <option value="{{ $schedule->id }}" 
                                                    {{ (old("schedules.{$dayNum}") ?? ($employeeSchedules[$dayNum]->work_schedule_id ?? null)) == $schedule->id ? 'selected' : '' }}>
                                                    {{ $schedule->name }} ({{ \Carbon\Carbon::parse($schedule->clock_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->clock_out)->format('H:i') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning" style="margin: 0;">
                                <i class="fas fa-exclamation-triangle"></i>
                                Belum ada jadwal kerja. <a href="{{ route('admin.work-schedules.create') }}">Buat jadwal kerja</a> terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <div class="card">
                    <div class="card-body" style="text-align: center; padding: 32px;">
                        <div class="avatar" style="width: 100px; height: 100px; margin: 0 auto 20px; font-size: 40px;">
                            @if($employee->face_photo_path)
                                <img src="{{ asset($employee->face_photo_path) }}" alt="{{ $employee->name }}">
                            @else
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            @endif
                        </div>
                        <h3 style="margin-bottom: 4px;">{{ $employee->name }}</h3>
                        <p style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 16px;">
                            {{ $employee->employee_number }}
                        </p>

                        <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 24px;">
                            @if($employee->hasFaceRegistered())
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle" style="margin-right: 4px;"></i>
                                    Wajah Terdaftar
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times-circle" style="margin-right: 4px;"></i>
                                    Wajah Belum Terdaftar
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('admin.employees.register-face', $employee) }}" class="btn btn-primary" style="width: 100%; margin-bottom: 8px;">
                            <i class="fas fa-camera"></i>
                            {{ $employee->hasFaceRegistered() ? 'Update Wajah' : 'Daftarkan Wajah' }}
                        </a>
                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary" style="width: 100%; margin-top: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('styles')
<style>
    @media (max-width: 1024px) {
        .page-content > form > div {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
