@extends('layouts.app')

@section('title', 'Edit Pasien BPJS')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Edit Data Pasien BPJS</h3>
    </div>
    <div class="add p-4">
        <form action="{{ route('updatepasienbpjs', $pasien->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="nomor_bpjs" class="form-label">Nomor Kartu BPJS :</label>
                <input type="text" class="form-control" id="nomor_bpjs" name="nomor_bpjs" value="{{ old('nomor_bpjs', $pasien->nomor_bpjs) }}" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama :</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $pasien->nama) }}" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat :</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat">{{ old('alamat', $pasien->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir :</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}" required>
            </div>
            <div class="mb-3">
                <label for="nik" class="form-label">NIK :</label>
                <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $pasien->nik) }}" required>
            </div>
            <div class="mb-3">
                <label for="faskes_tingkat_1" class="form-label">Faskes Tingkat 1 :</label>
                <input type="text" class="form-control" id="faskes_tingkat_1" name="faskes_tingkat_1" value="{{ old('faskes_tingkat_1', $pasien->faskes_tingkat_1) }}" required>
            </div>
            <div class="text-start">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
