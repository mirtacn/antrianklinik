@extends('layouts.app')

@section('title', 'Data Dokter')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Data Dokter</h3>
                    <form class="d-flex" method="GET" action="{{ route('dokter') }}">
                        <input class="form-search me-2" type="search" name="search" placeholder="Cari data dokter ..." aria-label="Search" value="{{ request('search') }}">
                        <button class="btn-cari" type="submit">Cari</button>
                    </form>
                </div>
                <a href="datadokter/tambah">
                    <button class="btn-tambah">+ Tambah Data Dokter</button>
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Foto Profil</th>
                                <th>Nama Dokter</th>
                                <th>Nama Spesialis</th>
                                <th>Nama Poli</th>
                                <th>Nomor Telepon</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dokter as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 + ($dokter->currentPage() - 1) * $dokter->perPage() }}</td>
                                    <td class="text-center">
                                        @if($item->foto_profil)
                                            <img src="{{ Storage::url($item->foto_profil) }}" alt="Foto Dokter" width="50" height="50"
                                                 style="object-fit: cover; border-radius: 50%; border: 2px solid #ccc;">
                                        @else
                                            <div style="width: 50px; height: 50px; border-radius: 50%; border: 2px dashed #ccc; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user" style="color: #aaa;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $item->nama_dokter }}</td>
                                    <td>{{ $item->nama_spesialis }}</td>
                                    <td>
                                        @if($item->polis->isNotEmpty())
                                            {{ $item->polis->pluck('nama_poli')->join(', ') }}
                                        @else
                                            Tidak ada poli
                                        @endif
                                    </td>
                                    <td>{{ $item->no_telepon }}</td>
                                    <td>
                                        <a href="{{ route('editdokter', $item->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('deletedokter', $item->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $item->id }}')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $dokter->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $dokter->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $dokter->lastPage(); $i++)
                                <li class="page-item {{ $i == $dokter->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $dokter->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $dokter->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $dokter->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
