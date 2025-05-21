@extends('layouts.app')

@section('title', 'Data Layanan')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Data Layanan</h3>
                    <form class="d-flex" method="GET" action="{{ route('layanan') }}">
                        <input class="form-search me-2" type="search" name="search" placeholder="  Cari data layanan ..."
                            aria-label="Search" value="{{ request('search') }}">
                        <button class="btn-cari" type="submit">Cari</button>
                    </form>
                </div>
                <a href="datalayanan/tambah">
                    <button class="btn-tambah">+ Tambah Data Layanan</button>
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Layanan</th>
                                <th>Deskripsi Layanan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($layanan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 + ($layanan->currentPage() - 1) * $layanan->perPage() }}</td>
                                    <td>{{ $item->nama_layanan }}</td>
                                    <td>{{ $item->deskripsi }}</td>
                                    <td>
                                        <a href="{{ route('editlayanan', $item->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('deletelayanan', $item->id) }}" method="POST"
                                            style="display:inline;" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $item->id }}')">
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
                            <li class="page-item {{ $layanan->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $layanan->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $layanan->lastPage(); $i++)
                                <li class="page-item {{ $i == $layanan->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $layanan->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $layanan->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $layanan->nextPageUrl() }}" aria-label="Next">
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
