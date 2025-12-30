@extends('layouts.admin')

@section('title', 'Payroll System')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Payroll</span>
@endsection

@section('content')
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="page-title">Payroll System</h1>
                <p class="page-description">Kelola dan generate gaji karyawan</p>
            </div>
            <a href="{{ route('admin.payrolls.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Generate Payroll
            </a>
        </div>
    </div>

    <!-- Filter Filter -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body">
            <form action="{{ route('admin.payrolls.index') }}" method="GET" style="display: flex; gap: 16px; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0; flex: 1;">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-control">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('month', $month) == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1;">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-control">
                        @foreach(range(date('Y')-2, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ request('year', $year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Potongan</th>
                            <th>THP (Net)</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                            <tr>
                                <td>
                                    <div style="font-weight: 500;">{{ $payroll->employee->name }}</div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">{{ $payroll->employee->employee_number }}</div>
                                </td>
                                <td>{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}</td>
                                <td>Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                                <td style="color: var(--color-success);">+ Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
                                <td style="color: var(--color-danger);">- Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                                <td style="font-weight: 700; color: var(--color-primary);">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-success">{{ ucfirst($payroll->status) }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.payrolls.show', $payroll) }}" class="btn btn-sm btn-info" title="Lihat Slip">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </a>
                                        <form action="{{ route('admin.payrolls.destroy', $payroll) }}" method="POST" onsubmit="return confirm('Hapus data payroll ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        <h3>Belum ada data payroll</h3>
                                        <p>Silakan generate payroll untuk periode ini.</p>
                                        <a href="{{ route('admin.payrolls.create') }}" class="btn btn-primary">Generate Sekarang</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div style="margin-top: 24px;">
        {{ $payrolls->withQueryString()->links() }}
    </div>
@endsection
