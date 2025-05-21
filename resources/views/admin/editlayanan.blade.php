@extends('layouts.app')

@section('title', 'Edit Data Layanan')

@section('content')
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Data Layanan</h3>
            </div>
            <div class="add p-4">
                <form action="{{ route('updatelayanan', $layanan->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <h4 class="text-center">Edit Data Layanan</h3>
                    <div class="mb-3">
                        <label for="namalayanan" class="form-label">Nama Layanan  :</label>
                        <input type="text" class="form-control" id="namalayanan" name="nama_layanan" value="{{ $layanan->nama_layanan }}">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsilayanan" class="form-label">Deskripsi Layanan :</label>
                        <input type="text" class="form-control" id="deskripsilayanan" name="deskripsi" value="{{ $layanan->deskripsi }}">
                    </div>
                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
