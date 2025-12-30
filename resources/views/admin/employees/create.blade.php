@extends('layouts.admin')

@section('title', 'Tambah Karyawan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.employees.index') }}">Karyawan</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Tambah</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Karyawan Baru</h1>
        <p class="page-description">Isi data karyawan dan atur jadwal kerja</p>
    </div>

    <form action="{{ route('admin.employees.store') }}" method="POST">
        @csrf
        
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
                                       value="{{ old('employee_number') }}" placeholder="EMP001" required>
                                @error('employee_number')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="John Doe" required>
                                @error('name')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" placeholder="john@example.com">
                                @error('email')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}" placeholder="08123456789">
                                @error('phone')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Departemen</label>
                                <select name="department" class="form-control @error('department') is-invalid @enderror">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Posisi/Jabatan</label>
                                <select name="position" class="form-control @error('position') is-invalid @enderror">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->name }}" {{ old('position') == $pos->name ? 'selected' : '' }}>{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                                @error('position')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
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
                            <p style="color: var(--color-text-secondary); margin-bottom: 20px; font-size: 14px;">
                                Pilih jadwal kerja untuk setiap hari kerja
                            </p>
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
                                                <option value="{{ $schedule->id }}" {{ old("schedules.{$dayNum}") == $schedule->id ? 'selected' : '' }}>
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

            <!-- Sidebar Info -->
            <div>
                <div class="card">
                    <div class="card-body" style="text-align: center; padding: 32px;">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--color-pastel-blue) 0%, var(--color-pastel-purple) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fas fa-user-plus" style="font-size: 40px; color: white;"></i>
                        </div>
                        <h3 style="margin-bottom: 8px;">Karyawan Baru</h3>
                        <p style="color: var(--color-text-secondary); font-size: 14px; margin-bottom: 24px;">
                            Setelah menyimpan data, Anda akan diarahkan ke halaman registrasi wajah.
                        </p>

                        <div style="background: var(--color-bg-primary); border-radius: var(--border-radius); padding: 16px; text-align: left; margin-bottom: 24px;">
                            <h4 style="font-size: 13px; color: var(--color-text-muted); margin-bottom: 12px;">LANGKAH SELANJUTNYA</h4>
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 24px; height: 24px; background: var(--color-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">1</div>
                                <span style="font-size: 14px;">Isi data karyawan</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 24px; height: 24px; background: var(--color-bg-tertiary); color: var(--color-text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">2</div>
                                <span style="font-size: 14px; color: var(--color-text-muted);">Registrasi wajah</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 24px; height: 24px; background: var(--color-bg-tertiary); color: var(--color-text-muted); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">3</div>
                                <span style="font-size: 14px; color: var(--color-text-muted);">Karyawan siap absen</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i>
                            Simpan & Lanjutkan
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
