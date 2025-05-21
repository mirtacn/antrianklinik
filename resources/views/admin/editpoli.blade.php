@extends('layouts.app')

@section('title', 'Edit Data Poli')

@section('content')
        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Data Poli</h3>
            </div>
            <div class="add p-4">
                <form action="{{ route('updatepoli', $poli->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <h4 class="text-center">Edit Data Poli</h4>
                    <div class="mb-3">
                        <label for="kodePoli" class="form-label">Kode Poli :</label>
                        <input type="text" class="form-control" id="kodePoli" name="kode_poli" value="{{ $poli->kode_poli }}">
                    </div>
                    <div class="mb-3">
                        <label for="namaPoli" class="form-label">Nama Poli :</label>
                        <input type="text" class="form-control" id="namaPoli" name="nama_poli" value="{{ $poli->nama_poli }}">
                    </div>
                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
@endsection
