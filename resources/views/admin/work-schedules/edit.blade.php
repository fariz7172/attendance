@extends('layouts.admin')

@section('title', 'Edit Jadwal Kerja')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.work-schedules.index') }}">Jadwal Kerja</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Edit</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Jadwal Kerja</h1>
        <p class="page-description">Perbarui jadwal {{ $workSchedule->name }}</p>
    </div>

    <div style="max-width: 600px;">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.work-schedules.update', $workSchedule) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nama Jadwal <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $workSchedule->name) }}" required>
                        @error('name')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Jam Masuk <span style="color: #ef4444;">*</span></label>
                            <input type="time" name="clock_in" class="form-control @error('clock_in') is-invalid @enderror" 
                                   value="{{ old('clock_in', \Carbon\Carbon::parse($workSchedule->clock_in)->format('H:i')) }}" required>
                            @error('clock_in')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jam Pulang <span style="color: #ef4444;">*</span></label>
                            <input type="time" name="clock_out" class="form-control @error('clock_out') is-invalid @enderror" 
                                   value="{{ old('clock_out', \Carbon\Carbon::parse($workSchedule->clock_out)->format('H:i')) }}" required>
                            @error('clock_out')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Mulai Istirahat</label>
                            <input type="time" name="break_start" class="form-control @error('break_start') is-invalid @enderror" 
                                   value="{{ old('break_start', $workSchedule->break_start ? \Carbon\Carbon::parse($workSchedule->break_start)->format('H:i') : '') }}">
                            @error('break_start')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Selesai Istirahat</label>
                            <input type="time" name="break_end" class="form-control @error('break_end') is-invalid @enderror" 
                                   value="{{ old('break_end', $workSchedule->break_end ? \Carbon\Carbon::parse($workSchedule->break_end)->format('H:i') : '') }}">
                            @error('break_end')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Toleransi Keterlambatan (menit) <span style="color: #ef4444;">*</span></label>
                        <input type="number" name="late_tolerance" class="form-control @error('late_tolerance') is-invalid @enderror" 
                               value="{{ old('late_tolerance', $workSchedule->late_tolerance) }}" min="0" max="60" required>
                        @error('late_tolerance')
                            <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $workSchedule->is_active) ? 'checked' : '' }}
                                   style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Jadwal Aktif</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 24px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
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
