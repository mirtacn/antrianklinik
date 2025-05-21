@extends('layouts.app')

@section('title', 'Tambah Jadwal Dokter')

@section('content')
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Data Jadwal Dokter</h3>
        </div>
        <div class="add p-4">
            <form action="{{ route('simpanjadwaldokter') }}" method="POST">
                @csrf
                <h4 class="text-center">Tambah Data Jadwal Dokter</h4>

                <!-- Tampilkan pesan error validasi -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="id_dokter" class="form-label">Dokter :</label>
                    <select name="id_dokter" id="id_dokter" class="form-control" required>
                        <option value="">Pilih Dokter</option>
                        @foreach ($dokter as $d)
                            <option value="{{ $d->id }}" {{ old('id_dokter') == $d->id ? 'selected' : '' }}>
                                {{ $d->nama_dokter }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_poli" class="form-label">Poli :</label>
                    <select name="id_poli" id="id_poli" class="form-control" required>
                        <option value="">Pilih Poli</option>
                        @foreach ($poli as $p)
                            <option value="{{ $p->id }}" {{ old('id_poli') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="hari" class="form-label">Hari :</label>
                    <select name="hari" id="hari" class="form-control" required>
                        <option value="">Pilih Hari</option>
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>
                                {{ $hari }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai :</label>
                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                </div>
                <div class="mb-3">
                    <label for="jam_selesai" class="form-label">Jam Selesai :</label>
                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                </div>
                <div class="mb-3">
                    <label for="kuotasisa" class="form-label">Kuota Sisa</label>
                    <input type="number" class="form-control" id="kuotasisa" name="kuotasisa" value="{{ old('kuotasisa') }}" required>
                </div>
                <div class="text-start">
                    <button type="submit" class="btn btn-submit w-20">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection