@extends('layouts.app')

@section('title', 'Tambah Data Dokter')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Data Dokter</h3>
        </div>
        <div class="add p-4">
            <form action="{{ route('simpandatadokter') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h4 class="text-center">Tambah Data Dokter</h4>
                <div class="mb-3">
                    <label for="foto_profil" class="form-label">Foto Profil :</label>
                    <input type="file" name="foto_profil" class="form-control" accept="image/*">
                    <small class="text-muted">Ukuran maksimal 2MB. Format: JPG, JPEG, PNG</small>
                </div>
                <div class="mb-3">
                    <label for="nama_dokter" class="form-label">Nama Dokter :</label>
                    <input type="text" name="nama_dokter" class="form-control" required
                    placeholder="Masukkan nama dokter" required>
                </div>
                <div class="mb-3">
                    <label for="nama_spesialis" class="form-label">Nama Spesialis :</label>
                    <input type="text" name="nama_spesialis" class="form-control" required
                    placeholder="Masukkan nama spesialis" required>
                </div>
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">Nomor Telepon :</label>
                    <input type="text" name="no_telepon" class="form-control" required  placeholder="Masukkan nomor telepon" required>
                </div>
                <div class="mb-3">
                    <label for="id_poli"  class="form-label">Poli :</label>
                    <select name="id_poli[]" class="form-control" multiple required>
                        @foreach($poli as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection