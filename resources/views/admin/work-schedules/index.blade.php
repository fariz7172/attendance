@extends('layouts.admin')

@section('title', 'Jadwal Kerja')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Jadwal Kerja</span>
@endsection

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Jadwal Kerja</h1>
            <p class="page-description">Kelola jadwal shift dan jam kerja</p>
        </div>
        <a href="{{ route('admin.work-schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Jadwal
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($schedules->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Jadwal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Istirahat</th>
                                <th>Toleransi Telat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--color-pastel-blue) 0%, var(--color-pastel-purple) 100%); border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-clock" style="color: white;"></i>
                                            </div>
                                            <strong>{{ $schedule->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="background: rgba(134, 212, 169, 0.2); color: #15803d; padding: 4px 12px; border-radius: 20px; font-weight: 500;">
                                            {{ \Carbon\Carbon::parse($schedule->clock_in)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="background: rgba(245, 168, 168, 0.2); color: #dc2626; padding: 4px 12px; border-radius: 20px; font-weight: 500;">
                                            {{ \Carbon\Carbon::parse($schedule->clock_out)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($schedule->break_start && $schedule->break_end)
                                            {{ \Carbon\Carbon::parse($schedule->break_start)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->break_end)->format('H:i') }}
                                        @else
                                            <span style="color: var(--color-text-muted);">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $schedule->late_tolerance }} menit</td>
                                    <td>
                                        @if($schedule->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.work-schedules.edit', $schedule) }}" class="btn btn-secondary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.work-schedules.destroy', $schedule) }}" method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <h3>Belum Ada Jadwal</h3>
                    <p>Buat jadwal kerja untuk mengatur jam masuk dan pulang.</p>
                    <a href="{{ route('admin.work-schedules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Tambah Jadwal
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
