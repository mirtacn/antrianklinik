@extends('layouts.app')

@section('title', 'Edit Pasien Umum')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Edit Data Pasien Umum</h3>
    </div>
    <div class="add p-4">
        <form action="{{ route('updatepasienumum', $pasien->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="nik" class="form-label">NIK :</label>
                <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $pasien->nik) }}" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama :</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $pasien->nama) }}" required>
            </div>
            <div class="mb-3">
                <label for="tempat_lahir" class="form-label">Tempat Lahir :</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $pasien->tempat_lahir) }}" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir :</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}" required>
            </div>
            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin :</label>
                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat :</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat">{{ old('alamat', $pasien->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label for="nama_ibu" class="form-label">Nama Ibu :</label>
                <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu', $pasien->nama_ibu) }}" required>
            </div>
            <div class="mb-3">
                <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir :</label>
                <input type="text" class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $pasien->pendidikan_terakhir) }}" required>
            </div>
            <div class="text-start">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
