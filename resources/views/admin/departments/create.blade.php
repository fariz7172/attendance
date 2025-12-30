@extends('layouts.admin')

@section('title', 'Tambah Departemen')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.departments.index') }}">Departemen</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Tambah</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Tambah Departemen</h1>
        <p class="page-description">Buat data departemen baru</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.departments.store') }}" method="POST" style="max-width: 600px;">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nama Departemen <span style="color: red;">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" placeholder="Contoh: IT, HR, Finance">
                    @error('name')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
