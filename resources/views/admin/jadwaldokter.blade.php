@extends('layouts.app')

@section('title', 'Data Jadwal Dokter')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Data Jadwal Dokter</h3>
                    <form class="d-flex">
                        <input class="form-search me-2" type="search" name="search" placeholder="  Cari jadwal dokter ..."
                            aria-label="Search" value="{{ request('search') }}">
                        <button class="btn-cari" type="submit">Cari</button>
                    </form>
                </div>
                <a href="datajadwaldokter/tambah">
                    <button class="btn-tambah">+ Tambah Jadwal Dokter</button>
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Dokter</th>
                                <th>Nama Poli</th>
                                <th>Hari</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Kuota Sisa</th>
                                <th>Kuota Diambil</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwaldokter as $index => $jadwal)
                                <tr>
                                    <td>{{ $index + $jadwaldokter->firstItem() }}</td>
                                    <td>{{ $jadwal->dokter->nama_dokter }}</td>
                                    <td>{{ $jadwal->poli->nama_poli }}</td>
                                    <td>{{ $jadwal->hari }}</td>
                                    <td>{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</td>
                                    <td>{{ date('H:i', strtotime($jadwal->jam_selesai)) }}</td>
                                    <td>{{ $jadwal->kuotasisa }}</td>
                                    <td>{{ $jadwal->kuotadiambil }}</td>
                                    <td>
                                        <a href="{{ route('editjadwaldokter', $jadwal->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('deletejadwaldokter', $jadwal->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $jadwal->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $jadwal->id }}')">
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
                            <li class="page-item {{ $jadwaldokter->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $jadwaldokter->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $jadwaldokter->lastPage(); $i++)
                                <li class="page-item {{ $i == $jadwaldokter->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $jadwaldokter->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $jadwaldokter->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $jadwaldokter->nextPageUrl() }}" aria-label="Next">
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
