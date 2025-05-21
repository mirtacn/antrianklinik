@extends('layouts.app')

@section('title', 'Tambah Pasien Umum')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Data Pasien Umum</h3>
        </div>
        <div class="add p-4">
            <form action="{{ route('simpandatapasienumum') }}" method="POST">
                @csrf
                <h4 class="text-center">Tambah Data Pasien Umum</h4>

                <div class="mb-3">
                    <label for="nik" class="form-label">NIK :</label>
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK">
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama :</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama pasien">
                </div>

                <div class="mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir :</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan tempat lahir">
                </div>

                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir :</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                </div>

                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin :</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat :</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat"></textarea>
                </div>

                <div class="mb-3">
                    <label for="nama_ibu" class="form-label">Nama Ibu :</label>
                    <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" placeholder="Masukkan nama ibu kandung">
                </div>

                <div class="mb-3">
                    <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir :</label>
                    <input type="text" class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" placeholder="Masukkan pendidikan terakhir">
                </div>

                <div class="text-start">
                    <button type="submit" class="btn btn-submit w-20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
