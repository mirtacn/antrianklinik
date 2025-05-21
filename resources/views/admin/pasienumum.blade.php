@extends('layouts.app')

@section('title', 'Data Pasien Umum')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Data Pasien Umum</h3>
                    <form class="d-flex">
                        <input class="form-search me-2" type="search" name="search" placeholder="  Cari NIK atau Nama..."
                            aria-label="Search" value="{{ request('search') }}">
                        <button class="btn-cari" type="submit">Cari</button>
                    </form>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('tambahpasienumum') }}">
                    <button class="btn-tambah">+ Tambah Pasien Umum</button>
                </a>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tempat, Tgl Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>Nama Ibu</th>
                                <th>Pendidikan Terakhir</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasien as $index => $p)
                                <tr>
                                    <td>{{ $index + 1 + ($pasien->currentPage() - 1) * $pasien->perPage() }}</td>
                                    <td>{{ $p->nik }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->tempat_lahir }}, {{ \Carbon\Carbon::parse($p->tanggal_lahir)->format('d-m-Y') }}</td>
                                    <td>{{ $p->jenis_kelamin }}</td>
                                    <td>{{ $p->alamat }}</td>
                                    <td>{{ $p->nama_ibu }}</td>
                                    <td>{{ $p->pendidikan_terakhir }}</td>
                                    <td>
                                        <a href="{{ route('editpasienumum', $p->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('deletepasienumum', $p->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $p->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $p->id }}')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
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

    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection