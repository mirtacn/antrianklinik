@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Edit Data Dokter</h3>
    </div>
    <div class="add p-4">
        <form action="{{ route('updatedokter', $dokter->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <label for="nama_dokter" class="form-label">Nama Dokter :</label>
                <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" value="{{ old('nama_dokter', $dokter->nama_dokter) }}" required>
            </div>
            <div class="mb-3">
                <label for="foto_profil" class="form-label">Foto Profil :</label>
                <input type="file" name="foto_profil" class="form-control" accept="image/*">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                @if($dokter->foto_profil)
                    <div class="mt-2">
                        <img src="{{ asset($dokter->foto_profil) }}" alt="Foto Profil" width="100">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="nama_spesialis" class="form-label">Nama Spesialis :</label>
                <input type="text" class="form-control" id="nama_spesialis" name="nama_spesialis" value="{{ old('nama_spesialis', $dokter->nama_spesialis) }}" required>
            </div>
            <div class="mb-3">
                <label for="no_telepon" class="form-label">Nomor Telepon :</label>
                <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $dokter->no_telepon) }}" required>
            </div>
            <div class="mb-3">
                <label for="id_poli" class="form-label">Poli :</label>
                <select name="id_poli[]" id="id_poli" class="form-control" multiple>
                    @foreach($poli as $p)
                        <option value="{{ $p->id }}" {{ in_array($p->id, old('id_poli', $dokter->polis->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $p->nama_poli }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="text-start">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection