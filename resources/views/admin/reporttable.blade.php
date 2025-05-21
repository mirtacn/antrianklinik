@extends('layouts.app')

@section('title', 'Report Table')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Report Table</h3>
                    <div>
                        <a href="{{ route('export.excel', request()->query()) }}" class="btn btn-success">Export to Excel</a>
                        <a href="{{ route('export.pdf', request()->query()) }}" class="btn btn-danger">Export to PDF</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nomor Antrian</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Dokter</th>
                                <th>Layanan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrians as $index => $antrian)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $antrian->kode_poli }}{{ $antrian->no_antrian }}</td>
                                <td>{{ $antrian->nama }}</td>
                                <td>{{ $antrian->nama_poli }}</td>
                                <td>{{ $antrian->nama_dokter ?? '-' }}</td>
                                <td>{{ $antrian->nama_layanan }}</td>
                                <td>{{ $antrian->tanggal_antrian }}</td>
                                <td>{{ $antrian->waktu_pilih }}</td>
                                <td>{{ $antrian->status_antrian }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection