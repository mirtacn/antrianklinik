<!DOCTYPE html>
<html>
<head>
    <title>Informasi Antrian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            color: #2c3e50;
            text-align: center;
        }
        .divider {
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h2 class="header">Dear {{ $antrian->nama }},</h2>

    <p>Terima kasih telah melakukan pendaftaran di Klinik Mabarrot Hasyimiyah Manyar Gresik. Berikut detail antrian Anda:</p>

    <div class="divider"></div>

    <div class="details">
        <p><strong>Layanan:</strong> {{ $antrian->nama_poli }}</p>
        <p><strong>Dokter:</strong> {{ $antrian->nama_dokter ?? '-' }}</p>
        <p><strong>Tanggal Antrian:</strong> {{ \Carbon\Carbon::parse($antrian->tanggal_antrian)->translatedFormat('d F Y') }}</p>
        <p><strong>Estimasi Dilayani:</strong> {{ $antrian->waktu_estimasi }}</p>
        <p><strong>Nomor Antrian:</strong> {{ $antrian->kode_poli }}{{ $antrian->no_antrian }}</p>
    </div>

    <div class="divider"></div>

    <p><strong>Catatan:</strong></p>
    <ol>
        <li>Harap datang sebelum waktu estimasi</li>
        <li>Jenis layanan tidak dapat diubah setelah pendaftaran</li>
        <li>Jika ada pertanyaan, silahkan hubungi kami</li>
    </ol>

    <div class="footer">
        <p>Salam hangat,</p>
        <p><strong>Tim Layanan Klinik Mabarrot Hasyimiyah</strong></p>
    </div>
</body>
</html>
