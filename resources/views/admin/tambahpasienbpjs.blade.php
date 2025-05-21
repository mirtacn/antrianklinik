@extends('layouts.app')

@section('title', 'Tambah Pasien BPJS')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Data Pasien BPJS</h3>
        </div>
        <div class="add p-4">
            <form action="{{ route('simpanpasienbpjs') }}" method="POST">
                @csrf
                <h4 class="text-center">Tambah Data Pasien BPJS</h4>

                <div class="mb-3">
                    <label for="nomor_bpjs" class="form-label">Nomor Kartu BPJS :</label>
                    <input type="text" class="form-control" id="nomor_bpjs" name="nomor_bpjs" placeholder="Masukkan nomor kartu BPJS" value="{{ old('nomor_bpjs') }}">
                    @error('nomor_bpjs') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="nik" class="form-label">NIK :</label>
                    <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" value="{{ old('nik') }}">
                    @error('nik') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama :</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama pasien" value="{{ old('nama') }}">
                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir :</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat :</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
                    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="faskes_tingkat_1" class="form-label">Faskes Tingkat 1 :</label>
                    <input type="text" class="form-control" id="faskes_tingkat_1" name="faskes_tingkat_1" placeholder="Masukkan Faskes Tingkat 1" value="{{ old('faskes_tingkat_1') }}">
                    @error('faskes_tingkat_1') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="text-start">
                    <button type="submit" class="btn btn-submit w-20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
