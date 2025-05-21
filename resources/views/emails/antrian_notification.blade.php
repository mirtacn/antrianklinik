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
        .status-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
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
        <p><strong>Nomor Antrian Anda:</strong> {{ $antrian->kode_poli }}{{ $antrian->no_antrian }}</p>
        <p><strong>Status Antrian:</strong>
            @if($antrian->status_antrian == 'menunggu')
                <span style="color: #ffc107; font-weight: bold;">Menunggu</span>
            @elseif($antrian->status_antrian == 'dipanggil')
                <span style="color: #0d6efd; font-weight: bold;">Sedang Dipanggil</span>
            @elseif($antrian->status_antrian == 'selesai')
                <span style="color: #198754; font-weight: bold;">Selesai</span>
            @else
                {{ $antrian->status_antrian }}
            @endif
        </p>
    </div>

    <div class="divider"></div>

    <div class="status-info">
        <h4>Informasi Antrian Saat Ini => </h4>
        <p><strong>Nomor Antrian yang sedang dipanggil:</strong>{{ $antrian->current_called }}</p>
        <p><strong>Posisi Anda dalam antrian:</strong> {{ $antrian->your_position ?? '-' }} dari {{ $antrian->waiting_count}} pasien</p>
        <p><strong>Sisa pasien menunggu:</strong> {{ $antrian->waiting_count }} pasien</p>
    </div>

    <div class="divider"></div>

    <p><strong>Catatan:</strong></p>
    <ol>
        <li>Harap datang sebelum waktu estimasi</li>
        <li>Jenis layanan tidak dapat diubah setelah pendaftaran</li>
        <li>Jika ada pertanyaan, silahkan hubungi kami</li>
        <li>Anda dapat memantau status antrian melalui website kami melalui menu monitoring antrian</li>
    </ol>

    <div class="footer">
        <p>Salam hangat,</p>
        <p><strong>Tim Layanan Klinik Mabarrot Hasyimiyah</strong></p>
    </div>
</body>
</html>