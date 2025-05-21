@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="mb-0">Data Pasien</h3>
                    </div>
                    <div>
                        <form class="d-flex">
                            <input class="form-search me-2" type="search" name="search" placeholder="Cari data pasien..." aria-label="Search" value="{{ request('search') }}">
                            <button class="btn-cari" type="submit">Cari</button>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nomor KTP</th>
                                <th>Nomor Telepon</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pasien as $index => $p)
                            <tr>
                                <td>{{ ($pasien->currentPage() - 1) * $pasien->perPage() + $index + 1 }}</td>
                                <td>{{ $p->nama_lengkap }}</td>
                                <td>{{ $p->no_ktp }}</td>
                                <td>{{ $p->no_telepon }}</td>
                                <td>{{ $p->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $pasien->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pasien->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $pasien->lastPage(); $i++)
                                <li class="page-item {{ $i == $pasien->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $pasien->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $pasien->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $pasien->nextPageUrl() }}" aria-label="Next">
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