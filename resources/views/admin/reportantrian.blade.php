@extends('layouts.app')

@section('title', 'Report Antrian')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Laporan Data Antrian Per Poli</h3>
                </div>
                <form class="mb-4" action="{{ route('report.table') }}" method="GET">
                    <div class="mb-3">
                        <label for="bulan" class="form-label">Pilih Bulan:</label>
                        <select class="form-select" id="bulan" name="bulan">
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="layanan" class="form-label">Pilih Poli:</label>
                        <select class="form-select" id="layanan" name="layanan">
                            @foreach($poli as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                        @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lihat Tabel Laporan</button>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Existing JavaScript functions
    function panggilAntrian(antrianId) {
        alert('Panggil antrian ID: ' + antrianId);
        // Tambahkan logika AJAX atau lainnya di sini
    }

    function ulangiPanggil(antrianId) {
        alert('Ulangi panggil antrian ID: ' + antrianId);
        // Tambahkan logika AJAX atau lainnya di sini
    }

    function kirimEmail(antrianId) {
        alert('Kirim email untuk antrian ID: ' + antrianId);
        // Tambahkan logika AJAX atau lainnya di sini
    }

    function selesaiAntrian(antrianId) {
        alert('Selesai antrian ID: ' + antrianId);
        // Tambahkan logika AJAX atau lainnya di sini
    }
</script>
@endpush