@extends('layouts.admin')

@section('title', 'Daftar Karyawan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Karyawan</span>
@endsection

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Daftar Karyawan</h1>
            <p class="page-description">Kelola data karyawan dan registrasi wajah</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Karyawan
        </a>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-body">
            <form action="{{ route('admin.employees.index') }}" method="GET" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="margin: 0; flex: 1; min-width: 200px;">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, No. Karyawan, Departemen..." value="{{ request('search') }}">
                </div>
                <div class="form-group" style="margin: 0; min-width: 150px;">
                    <label class="form-label">Departemen</label>
                    <select name="department" class="form-control">
                        <option value="">Semua</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin: 0; min-width: 150px;">
                    <label class="form-label">Status Wajah</label>
                    <select name="face_status" class="form-control">
                        <option value="">Semua</option>
                        <option value="registered" {{ request('face_status') == 'registered' ? 'selected' : '' }}>Terdaftar</option>
                        <option value="not_registered" {{ request('face_status') == 'not_registered' ? 'selected' : '' }}>Belum Terdaftar</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                @if(request()->hasAny(['search', 'department', 'face_status']))
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($employees->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>No. Karyawan</th>
                                <th>Departemen</th>
                                <th>Posisi</th>
                                <th>Status Wajah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="avatar">
                                                @if($employee->face_photo_path)
                                                    <img src="{{ asset($employee->face_photo_path) }}" alt="">
                                                @else
                                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div style="font-weight: 500;">{{ $employee->name }}</div>
                                                @if($employee->email)
                                                    <div style="font-size: 12px; color: var(--color-text-muted);">{{ $employee->email }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code style="background: var(--color-bg-tertiary); padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                                            {{ $employee->employee_number }}
                                        </code>
                                    </td>
                                    <td>{{ $employee->department ?? '-' }}</td>
                                    <td>{{ $employee->position ?? '-' }}</td>
                                    <td>
                                        <div class="face-status">
                                            <span class="face-status-dot {{ $employee->hasFaceRegistered() ? 'registered' : 'not-registered' }}"></span>
                                            @if($employee->hasFaceRegistered())
                                                <span style="color: #15803d; font-size: 13px;">Terdaftar</span>
                                            @else
                                                <span style="color: #dc2626; font-size: 13px;">Belum</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($employee->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.employees.show', $employee) }}" class="btn btn-secondary btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.employees.register-face', $employee) }}" class="btn btn-primary btn-sm" title="Registrasi Wajah">
                                                <i class="fas fa-camera"></i>
                                            </a>
                                            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-secondary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
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

                @if($employees->hasPages())
                    <div style="padding: 16px;">
                        {{ $employees->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>Belum Ada Karyawan</h3>
                    <p>Mulai dengan menambahkan karyawan baru.</p>
                    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Tambah Karyawan
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
