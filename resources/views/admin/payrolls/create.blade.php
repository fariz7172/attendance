@extends('layouts.admin')

@section('title', 'Generate Payroll')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.payrolls.index') }}">Payroll</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Generate</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Generate Payroll</h1>
        <p class="page-description">Hitung gaji karyawan secara otomatis berdasarkan absensi.</p>
    </div>

    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                   Sistem akan menghitung gaji untuk <strong>semua karyawan aktif</strong> yang memiliki Jabatan.
                   <br>Rumus: <code>Gaji Pokok + (Uang Makan x Kehadiran) - (Denda x Menit Telat)</code>
                </div>
            </div>

            <form action="{{ route('admin.payrolls.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-control">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-control">
                        @foreach(range(date('Y')-1, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="regenerate" value="1" style="width: 18px; height: 18px;">
                        <span>Regenerate (Hapus data lama jika sudah ada)</span>
                    </label>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cogs"></i>
                        Proses Hitung Gaji
                    </button>
                    <a href="{{ route('admin.payrolls.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
