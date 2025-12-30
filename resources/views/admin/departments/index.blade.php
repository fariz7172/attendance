@extends('layouts.admin')

@section('title', 'Daftar Departemen')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Departemen</span>
@endsection

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Daftar Departemen</h1>
            <p class="page-description">Kelola data departemen perusahaan</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Departemen
        </a>
    </div>

    <!-- Departments Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($departments->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Departemen</th>
                                <th>Dibuat Pada</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $index => $department)
                                <tr>
                                    <td>{{ $departments->firstItem() + $index }}</td>
                                    <td>
                                        <div style="font-weight: 500;">{{ $department->name }}</div>
                                    </td>
                                    <td>{{ $department->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-secondary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Yakin ingin menghapus departemen ini?');">
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

                @if($departments->hasPages())
                    <div style="padding: 16px;">
                        {{ $departments->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <h3>Belum Ada Departemen</h3>
                    <p>Mulai dengan menambahkan departemen baru.</p>
                    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Tambah Departemen
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
