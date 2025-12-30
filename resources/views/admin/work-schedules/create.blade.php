@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kerja')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.work-schedules.index') }}">Jadwal Kerja</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Tambah</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Jadwal Kerja</h1>
        <p class="page-description">Buat jadwal shift baru dengan jam masuk, pulang, dan istirahat</p>
    </div>

    <div style="max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.work-schedules.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Jadwal <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Contoh: Shift Pagi, Shift Siang" required>
                        @error('name')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Jam Masuk <span style="color: #ef4444;">*</span></label>
                            <input type="time" name="clock_in" class="form-control @error('clock_in') is-invalid @enderror" 
                                   value="{{ old('clock_in', '08:00') }}" required>
                            @error('clock_in')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jam Pulang <span style="color: #ef4444;">*</span></label>
                            <input type="time" name="clock_out" class="form-control @error('clock_out') is-invalid @enderror" 
                                   value="{{ old('clock_out', '17:00') }}" required>
                            @error('clock_out')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Mulai Istirahat</label>
                            <input type="time" name="break_start" class="form-control @error('break_start') is-invalid @enderror" 
                                   value="{{ old('break_start', '12:00') }}">
                            @error('break_start')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Selesai Istirahat</label>
                            <input type="time" name="break_end" class="form-control @error('break_end') is-invalid @enderror" 
                                   value="{{ old('break_end', '13:00') }}">
                            @error('break_end')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Toleransi Keterlambatan (menit) <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="late_tolerance" class="form-control @error('late_tolerance') is-invalid @enderror" 
                               value="{{ old('late_tolerance', 15) }}" min="0" max="60" required>
                        <p style="font-size: 12px; color: var(--color-text-muted); margin-top: 4px;">
                            Karyawan masih dianggap tepat waktu jika absen dalam toleransi ini
                        </p>
                        @error('late_tolerance')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Jadwal
                        </button>
                        <a href="{{ route('admin.work-schedules.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
