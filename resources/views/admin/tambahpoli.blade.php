@extends('layouts.app')

@section('title', 'Tambah Data Poli')

@section('content')
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Data Poli</h3>
            </div>
            <div class="add p-4">
                <form action="{{ route('simpandatapoli') }}" method="POST">
                    @csrf
                    <h4 class="text-center">Tambah Data Poli</h4>
                    <div class="mb-3">
                        <label for="kodePoli" class="form-label">Kode Poli :</label>
                        <input type="text" class="form-control" id="kodePoli" name="kode_poli"
                        placeholder="Masukkan 1 huruf kapital saja" maxlength="1" pattern="[A-Z]" required>
                    </div>
                    <div class="mb-3">
                        <label for="namaPoli" class="form-label">Nama Poli :</label>
                        <input type="text" class="form-control" id="namaPoli" name="nama_poli"
                        placeholder="Masukkan nama poli" required>
                    </div>
                    <div class="text-start">
                        <button type="submit" class="btn btn-submit w-20">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
