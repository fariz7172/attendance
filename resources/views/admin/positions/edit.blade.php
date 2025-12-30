@extends('layouts.admin')

@section('title', 'Edit Jabatan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('admin.positions.index') }}">Jabatan</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Edit</span>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Jabatan</h1>
        <p class="page-description">Perbarui data jabatan</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.positions.update', $position) }}" method="POST" style="max-width: 600px;">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Nama Jabatan <span style="color: red;">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $position->name) }}" placeholder="Contoh: Manager, Staff, Supervisor">
                    @error('name')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Gaji Pokok (Rp) <span style="color: red;">*</span></label>
                    <input type="text" name="basic_salary" class="form-control currency-input @error('basic_salary') is-invalid @enderror" 
                           value="{{ old('basic_salary', number_format($position->basic_salary, 0, ',', '.')) }}" required>
                    @error('basic_salary')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Uang Makan Harian (Rp) <span style="color: red;">*</span></label>
                    <input type="text" name="meal_allowance" class="form-control currency-input @error('meal_allowance') is-invalid @enderror" 
                           value="{{ old('meal_allowance', number_format($position->meal_allowance, 0, ',', '.')) }}" required>
                    @error('meal_allowance')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Potongan Jika Tidak Masuk (Per Hari) <span style="color: red;">*</span></label>
                    <input type="text" name="absent_fee" class="form-control currency-input @error('absent_fee') is-invalid @enderror" 
                           value="{{ old('absent_fee', number_format($position->absent_fee, 0, ',', '.')) }}" required>
                    @error('absent_fee')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.positions.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currencyInputs = document.querySelectorAll('.currency-input');
        
        currencyInputs.forEach(input => {
            // Event listener
            input.addEventListener('keyup', function(e) {
                input.value = formatRupiah(this.value);
            });
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    });
</script>
@endpush
