<!DOCTYPE html>
<html>

<head>
    <title>Struk Antrian</title>
    <style>
        body {
            font-family: 'Manrope', sans-serif;
            padding: 20px;
        }

        h2 {
            font-size: 14px;
            font-weight: 700;
        }

        h1 {
            font-size: 40px;
            font-weight: 700;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-container {
            margin-left: 50px;
            margin-right: 50px;
            padding: 50px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 50px;
        }

        .tittle-tiket {
            font-size: 25px;
            color: #000000;
            font-family: 'Manrope', sans-serif;
            text-align: center;
            font-weight: 900;
        }

        .tittle-klinik {
            margin-top: 35px;
            font-size: 20px;
            color: #000000;
            font-family: 'Manrope', sans-serif;
            text-align: center;
        }

        .tittle-alamat {
            margin-top: 17px;
            font-size: 15px;
            color: #646464;
            font-family: 'Manrope', sans-serif;
            text-align: center;
            font-weight: 500;
            margin: 0;
            padding: 0;
        }

        .nomor-antrian {
            font-size: 100px;
            color: #000000;
            font-family: 'Manrope', sans-serif;
            text-align: center;
            font-weight: 7 00;
            margin: 0;
            padding: 0;
        }

        .nomor-NIK {
            font-size: 20px;
            color: #000000;
            font-family: 'Manrope', sans-serif;
            text-align: center;
        }

        .nama {
            margin-top: 17px;
            font-size: 15px;
            color: #000000;
            font-family: 'Manrope', sans-serif;
            text-align: center;
            font-weight: 500;
        }

        .note {
            margin-top: 65px;
            font-size: 15px;
            color: #646464;
            font-family: 'Manrope', sans-serif;
            text-align: left;
            font-weight: 500;
        }

        .note-1 {
            margin-top: 15px;
            font-size: 15px;
            color: #646464;
            font-family: 'Manrope', sans-serif;
            text-align: left;
            font-weight: 500;
        }
    </style>
</head>

<body>
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
            {{-- <h2 class="nomor-NIK">{{ $antrian->pasien_umum->nik }}</h2>
            <h2 class="nama">{{ $antrian->pasien_umum->nama_lengkap }}</h2> --}}
            <h2 class="tittle-alamat">Estimasi Dilayani :</h2>
            <h2 class="nama">
                {{ date('d F Y', strtotime($antrian->tanggal_antrian)) }}
                Jam : {{ date('h:i A', strtotime($antrian->waktu_estimasi)) }}
                {{-- @php
                    use Carbon\Carbon;

                    $waktuPilih = Carbon::parse($antrian->waktu_pilih);
                    $namaPoli = strtolower($antrian->poli->nama_poli);

                    if ($namaPoli == 'poli umum') {
                        $waktuFinal = $waktuPilih->copy()->addMinutes(15);
                    } elseif ($namaPoli == 'poli kia' || $namaPoli == 'poli kb') {
                        $waktuFinal = $waktuPilih->copy()->addMinutes(15);
                    } elseif ($namaPoli == 'poli gigi') {
                        $waktuFinal = $waktuPilih->copy()->addMinutes(30);
                    } else {
                        $waktuFinal = $waktuPilih;
                    }
                @endphp
                {{ $waktuFinal->format('h:i A') }} --}}
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

</body>

</html>
