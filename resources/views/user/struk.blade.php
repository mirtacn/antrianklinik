@extends('layoutuser.app')

@section('title', 'Struk')

@section('content')
    <section id="pesan" class="py-5" id="printSection">
        <div class="container">
            <form class="mt-4 form-container bg-white p-20 rounded shadow">
                <h2 class="tittle-tiket">TIKET ANTRIAN</h2>
                <h2 class="tittle-klinik">Klinik Mabarrot Hasyimiyah Manyar Gresik</h2>
                <h2 class="tittle-alamat">Jl. Kyai Sahlan I No.21, Manyarejo, Kec. Manyar,
                    Kabupaten Gresik, Jawa Timur 61151, Indonesia</h2>
                <h2 class="tittle-alamat">Telp : +62 819-4966-6149</h2>
                <hr class="mt-4">
                <h2 class="tittle-klinik">{{ $antrian->poli->nama_poli }}</h2>
                <h1 class="nomor-antrian">{{ $antrian->poli->kode_poli }}{{ $antrian->no_antrian }}</h1>
                <h2 class="nama">Dokter: {{ $antrian->jadwaldokter->dokter->nama_dokter }}</h2>
                <h2 class="tittle-alamat">Estimasi Dilayani :</h2>
                <h2 class="nama">
                    {{ date('d F Y', strtotime($antrian->tanggal_antrian)) }}
                    Jam : {{ date('h:i A', strtotime($antrian->waktu_estimasi)) }}
                </h2>
                <h2 class="tittle-alamat">Layanan : {{ $antrian->layanan->nama_layanan }}</h2>
                @if ($antrian->pasienUmum)
                    <h2 class="nama">Nama Pasien: {{ $antrian->pasienUmum->nama }}</h2>
                @elseif ($antrian->pasienBPJS)
                    <h2 class="nama">Nama Pasien: {{ $antrian->pasienBPJS->nama }}</h2>
                @endif
                <h2 class="note">** Dimohon datang sebelum jam estimasi dilayani yang tertera pada struk</h2>
                <h2 class="note-1">** Pastikan jenis layanan Anda, perubahan jenis layanan nomor antrian tidak berlaku</h2>
            </form>
        </div>
        <div class="btn-container">
            <a href="{{ route('download.struk') }}" class="btn-unduh">
                <i class="bi bi-download"></i> Unduh struk antrian
            </a>
            <a href="/monitor" class="btn-monitor">
                <i class="bi bi-display"></i> Monitor Antrian
            </a>
            <a href="javascript:void(0);" onclick="printStruk();" class="btn-unduh">
                <i class="bi bi-printer"></i> Cetak struk antrian
            </a>
        </div>
    </section>

    <script>
        function printStruk() {
            var printContents = document.querySelector('.form-container').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
