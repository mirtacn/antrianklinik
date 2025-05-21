@extends('layouts.app')

@section('title', 'Data Poli')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Data Poli</h3>
                    <form class="d-flex">
                        <input class="form-search me-2" type="search" name="search" placeholder="  Cari data poli ..."
                            aria-label="Search" value="{{ request('search') }}">
                        <button class="btn-cari" type="submit">Cari</button>
                    </form>
                </div>
                <a href="datapoli/tambah">
                    <button class="btn-tambah">+ Tambah Data Poli</button>
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Poli</th>
                                <th>Nama Poli</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($poli as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 + ($poli->currentPage() - 1) * $poli->perPage() }}</td>
                                    <td>{{ $item->kode_poli }}</td>
                                    <td>{{ $item->nama_poli }}</td>
                                    <td>
                                        <a href="{{ route('editpoli', $item->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('deletepoli', $item->id) }}" method="POST"
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
                            <li class="page-item {{ $poli->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $poli->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $poli->lastPage(); $i++)
                                <li class="page-item {{ $i == $poli->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $poli->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $poli->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $poli->nextPageUrl() }}" aria-label="Next">
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
