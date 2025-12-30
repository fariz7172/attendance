@extends('layouts.admin')

@section('title', 'Daftar Jabatan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Jabatan</span>
@endsection

@section('content')
    <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Daftar Jabatan</h1>
            <p class="page-description">Kelola data jabatan/posisi karyawan</p>
        </div>
        <a href="{{ route('admin.positions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Jabatan
        </a>
    </div>

    <!-- Positions Table -->
    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($positions->count() > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Jabatan</th>
                                <th>Dibuat Pada</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($positions as $index => $position)
                                <tr>
                                    <td>{{ $positions->firstItem() + $index }}</td>
                                    <td>
                                        <div style="font-weight: 500;">{{ $position->name }}</div>
                                    </td>
                                    <td>{{ $position->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.positions.edit', $position) }}" class="btn btn-secondary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.positions.destroy', $position) }}" method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('Yakin ingin menghapus jabatan ini?');">
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

                @if($positions->hasPages())
                    <div style="padding: 16px;">
                        {{ $positions->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <h3>Belum Ada Jabatan</h3>
                    <p>Mulai dengan menambahkan jabatan baru.</p>
                    <a href="{{ route('admin.positions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Tambah Jabatan
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
