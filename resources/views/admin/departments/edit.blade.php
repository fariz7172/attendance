@extends('layouts.admin')

@section('title', 'Edit Departemen')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.departments.index') }}">Departemen</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Edit</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Departemen</h1>
        <p class="page-description">Perbarui data departemen</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.departments.update', $department) }}" method="POST" style="max-width: 600px;">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Nama Departemen <span style="color: red;">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $department->name) }}" placeholder="Contoh: IT, HR, Finance">
                    @error('name')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
