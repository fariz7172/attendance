@extends('layouts.admin')

@section('title', 'Slip Gaji - ' . $payroll->employee->name)

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.payrolls.index') }}">Payroll</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Slip Gaji</span>
@endsection

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;" class="no-print">
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="fas fa-print"></i> Cetak PDF
        </button>
    </div>

    <div class="card" id="payslip">
        <div class="card-body" style="padding: 40px;">
            <!-- Header -->
            <div style="text-align: center; margin-bottom: 40px; border-bottom: 2px solid #eee; padding-bottom: 20px;">
                <h1 style="font-size: 24px; font-weight: 800; color: var(--color-primary); margin-bottom: 8px;">PT. FACE ATTEND INDONESIA</h1>
                <p style="color: var(--color-text-secondary);">Jl. Teknologi No. 123, Jakarta Selatan</p>
                <h2 style="font-size: 20px; font-weight: 700; margin-top: 20px;">SLIP GAJI (PAYSLIP)</h2>
                <p style="color: var(--color-text-muted);">Periode: {{ date('F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)) }}</p>
            </div>

            <!-- Employee Info -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
                <table>
                    <tr>
                        <td style="padding: 4px 0; color: var(--color-text-secondary); width: 120px;">Nama</td>
                        <td style="padding: 4px 0; font-weight: 600;">: {{ $payroll->employee->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: var(--color-text-secondary);">No. Karyawan</td>
                        <td style="padding: 4px 0; font-weight: 600;">: {{ $payroll->employee->employee_number }}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="padding: 4px 0; color: var(--color-text-secondary); width: 120px;">Jabatan</td>
                        <td style="padding: 4px 0; font-weight: 600;">: {{ $payroll->employee->position ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: var(--color-text-secondary);">Departemen</td>
                        <td style="padding: 4px 0; font-weight: 600;">: {{ $payroll->employee->department ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Details -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px;">
                <!-- Earnings -->
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 16px; color: var(--color-success);">PENERIMAAN (EARNINGS)</h3>
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 8px 0;">Gaji Pokok</td>
                            <td style="text-align: right;">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;">
                                Uang Makan 
                                <br><small style="color: var(--color-text-muted); font-size: 11px;">({{ $payroll->attendance_summary['present_days'] ?? 0 }} hari x Rp {{ number_format($payroll->meal_allowance, 0, ',', '.') }})</small>
                            </td>
                            <td style="text-align: right; vertical-align: top;">Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Deductions -->
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 16px; color: var(--color-danger);">POTONGAN (DEDUCTIONS)</h3>
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 8px 0;">
                                Potongan Ketidakhadiran
                                <br><small style="color: var(--color-text-muted); font-size: 11px;">({{ $payroll->attendance_summary['absent_days'] ?? 0 }} hari x Rp {{ number_format($payroll->absent_fee, 0, ',', '.') }})</small>
                            </td>
                            <td style="text-align: right; vertical-align: top;">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div style="background: #f8f9fc; padding: 20px; border-radius: 12px; margin-bottom: 40px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 18px;">Total Penerimaan</td>
                        <td style="text-align: right; font-size: 18px;">Rp {{ number_format($payroll->basic_salary + $payroll->total_allowance, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; color: var(--color-danger);">Total Potongan</td>
                        <td style="text-align: right; font-size: 18px; color: var(--color-danger);">Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr style="margin: 16px 0; border: 0; border-top: 1px solid #ddd;"></td>
                    </tr>
                    <tr style="font-weight: 800; font-size: 24px; color: var(--color-primary);">
                        <td>TAKE HOME PAY</td>
                        <td style="text-align: right;">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Footer -->
            <div style="display: flex; justify-content: space-between; margin-top: 60px;">
                <div style="text-align: center;">
                    <p style="margin-bottom: 80px;">Penerima,</p>
                    <p style="font-weight: 600;">{{ $payroll->employee->name }}</p>
                </div>
                <div style="text-align: center;">
                    <p style="margin-bottom: 80px;">Jakarta, {{ now()->format('d F Y') }}<br>Finance Dept,</p>
                    <p style="font-weight: 600;">( ________________ )</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print, .sidebar, .top-header, .page-header, .breadcrumb {
                display: none !important;
            }
            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>
@endsection
