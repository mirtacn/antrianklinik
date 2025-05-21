<!DOCTYPE html>
<html>
<head>
    <title>Laporan Antrian</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .footer { margin-top: 20px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Antrian</h1>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
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
    <div class="footer">
        <p>Dicetak oleh Sistem Antrian</p>
    </div>
</body>
</html>
