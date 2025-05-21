@extends('layouts.app')

@section('title', 'Panggil Antrian')

@section('content')
    <div class="container-fluid p-4">
        <div class="row">
            <div class="card" style="width: 100%; padding: 20px; border: none;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Panggil Antrian</h3>
                    <form class="d-flex" id="filterForm">
                        <select class="form-select me-2" name="poli" id="poliSelect">
                            <option value="">Semua Poli</option>
                            <option value="Poli Umum" {{ request('poli') == 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                            <option value="Poli Gigi & Mulut" {{ request('poli') == 'Poli Gigi & Mulut' ? 'selected' : '' }}>Poli Gigi & Mulut</option>
                            <option value="Poli KIA" {{ request('poli') == 'Poli KIA' ? 'selected' : '' }}>Poli KIA</option>
                            <option value="UGD" {{ request('poli') == 'UGD' ? 'selected' : '' }}>UGD</option>
                        </select>
                        <!-- Removed the filter button -->
                    </form>
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
                                <th>Estimasi Dipanggil</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($antrians as $index => $antrian)
                            <tr>
                                <td>{{ ($antrians->currentPage() - 1) * $antrians->perPage() + $index + 1 }}</td>
                                <td>{{ $antrian->kode_poli }}{{ $antrian->no_antrian }}</td>
                                <td>{{ $antrian->nama }}</td>
                                <td>{{ $antrian->nama_poli }}</td>
                                <td>{{ $antrian->nama_dokter ?? '-' }}</td>
                                <td>{{ $antrian->nama_layanan }}</td>
                                <td>{{ \Carbon\Carbon::parse($antrian->tanggal_antrian)->translatedFormat('d F Y') }}</td>
                                <td>{{ $antrian->waktu_estimasi }}</td>
                                <td>
                                    <span class="badge
                                        @if($antrian->status_antrian == 'menunggu') bg-warning text-dark
                                        @elseif($antrian->status_antrian == 'dipanggil') bg-primary
                                        @elseif($antrian->status_antrian == 'selesai') bg-success
                                        @elseif($antrian->status_antrian == 'tidak hadir') bg-danger
                                        @elseif($antrian->status_antrian == 'pending') bg-secondary
                                        @endif">
                                        {{ $antrian->status_antrian }}
                                    </span>
                                </td>
                                <td>
                                    @if($antrian->status_antrian == 'menunggu')
                                        <button class="btn btn-sm btn-success me-1 call-btn" data-id="{{ $antrian->id }}"
                                            {{ $activeCallExists && $antrian->status_antrian != 'dipanggil' ? 'disabled' : '' }}>
                                            <i class="bi bi-bell-fill"></i> Panggil
                                        </button>
                                        <button class="btn btn-sm btn-danger me-1" onclick="ulangiPanggil({{ $antrian->id }})"
                                            {{ $activeCallExists && $antrian->status_antrian != 'dipanggil' ? 'disabled' : '' }}>
                                            <i class="bi bi-arrow-repeat"></i>
                                            Ulangi Panggil (<span id="call-count-{{ $antrian->id }}">{{ $antrian->panggilan_count }}</span>/3)
                                        </button>
                                        <button class="btn btn-sm btn-info me-1" onclick="kirimEmail({{ $antrian->id }})">
                                            <i class="bi bi-send-fill"></i> Kirim Email
                                        </button>
                                        @php
                                        $phone = preg_replace('/[^0-9]/', '', '+62'. $antrian->no_telepon. '');

                                        // Fetch the current called queue number and doctor for the same poli
                                        $currentCalled = $antrians->firstWhere('status_antrian', 'dipanggil');
                                        $currentQueueNumber = $currentCalled ? $currentCalled->kode_poli . $currentCalled->no_antrian : 'Tidak ada';


                                        $message = urlencode('Dear *' . $antrian->nama . '*,
Terima kasih telah melakukan pendaftaran di *Klinik Mabarrot Hasyimiyah Manyar Gresik*. Berikut detail antrian Anda:
────────────────
*Layanan:* ' . $antrian->nama_poli . '
*Dokter:* ' . $antrian->nama_dokter . '
*Tanggal Antrian:* ' . $antrian->tanggal_antrian . '
*Estimasi Dilayani:* ' . $antrian->waktu_estimasi . '
*Nomor Antrian:* '.$antrian->kode_poli.''. $antrian->no_antrian . '
*Status Antrian:* ' . $antrian->status_antrian . '
*Antrian Saat Ini:* ' . $currentQueueNumber . '
────────────────

*Catatan:*
1. Harap datang sebelum waktu estimasi
2. Jenis layanan tidak dapat diubah setelah pendaftaran
3.Jika ada pertanyaan, silahkan hubungi kami

Salam hangat,
*Tim Layanan Klinik Mabarrot Hasyimiyah*');
                                        $whatsappLink = "https://wa.me/{$phone}?text={$message}";
                                        @endphp

                                        <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-sm btn-success me-1" style="background-color: #297746; color:white">
                                            <i class="bi bi-whatsapp"></i> WhatsApp
                                        </a>
                                    @elseif($antrian->status_antrian == 'dipanggil')
                                        <button class="btn btn-sm btn-primary me-1" onclick="updateStatus({{ $antrian->id }}, 'selesai')">
                                            <i class="bi bi-check-circle-fill"></i> Selesai
                                        </button>
                                        <button class="btn btn-sm btn-secondary me-1" disabled>
                                            <i class="bi bi-bell-slash-fill"></i> Sudah Dipanggil
                                        </button>
                                        <button class="btn btn-sm btn-danger me-1" onclick="ulangiPanggil({{ $antrian->id }})">
                                            <i class="bi bi-arrow-repeat"></i> Ulangi Panggil ({{ $antrian->panggilan_count }}/3)
                                        </button>
                                    @elseif($antrian->status_antrian == 'tidak hadir')
                                        <button class="btn btn-sm btn-secondary me-1" disabled>
                                            <i class="bi bi-bell-slash-fill"></i> Tidak Hadir
                                        </button>
                                        <button class="btn btn-sm btn-secondary me-1" disabled>
                                            <i class="bi bi-arrow-repeat"></i> Ulangi Panggil
                                        </button>
                                    @elseif($antrian->status_antrian == 'pending')
                                        <button class="btn btn-sm btn-warning me-1" onclick="markAsNotPresent({{ $antrian->id }})">
                                            <i class="bi bi-person-x-fill"></i> Tidak Hadir
                                        </button>
                                        <button class="btn btn-sm btn-primary me-1" onclick="recallPending({{ $antrian->id }})">
                                            <i class="bi bi-bell-fill"></i> Panggil Ulang
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item {{ $antrians->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $antrians->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @for ($i = 1; $i <= $antrians->lastPage(); $i++)
                                <li class="page-item {{ $i == $antrians->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $antrians->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor
                            <li class="page-item {{ $antrians->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $antrians->nextPageUrl() }}" aria-label="Next">
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

@push('scripts')
<script>
// Listen for changes on the poli dropdown
document.getElementById('poliSelect').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

// Fungsi untuk mengucapkan panggilan antrian
function speakAntrian(noAntrian, namaPasien, namaDokter) {
    const text = `Nomor Antrian ${noAntrian} atas nama ${namaPasien}, silahkan menuju ke ruangan ${namaDokter}`;

    if ('speechSynthesis' in window) {
        // Hentikan pembicaraan yang sedang berlangsung
        window.speechSynthesis.cancel();

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID'; // Set bahasa Indonesia

        // Cari voice yang mendukung bahasa Indonesia jika ada
        const voices = window.speechSynthesis.getVoices();
        const indonesianVoice = voices.find(voice =>
            voice.lang.includes('id') || voice.lang.includes('ID'));

        if (indonesianVoice) {
            utterance.voice = indonesianVoice;
        } else {
            // Jika tidak ada voice Indonesia, coba cari voice yang bagus
            const preferredVoices = ['Microsoft Zira Desktop', 'Google US English', 'Microsoft David Desktop'];
            const goodVoice = voices.find(voice => preferredVoices.includes(voice.name));
            if (goodVoice) {
                utterance.voice = goodVoice;
            }
        }

        // Atur kecepatan dan pitch
        utterance.rate = 0.9; // Sedikit lebih lambat
        utterance.pitch = 1;
        utterance.volume = 1; // Volume maksimal

        window.speechSynthesis.speak(utterance);
    } else {
        console.warn('Text-to-Speech tidak didukung di browser ini');
        // Fallback: Play audio dari file jika tersedia
        // atau tampilkan notifikasi visual
        alert(`Nomor Antrian ${noAntrian} atas nama ${namaPasien}, silahkan menuju ke ruangan ${namaDokter}`);
    }
}

// Inisialisasi voices saat halaman dimuat
$(document).ready(function() {
    if ('speechSynthesis' in window) {
        // Beberapa browser memerlukan ini untuk memuat voices
        window.speechSynthesis.onvoiceschanged = function() {
            console.log('Voices loaded:', window.speechSynthesis.getVoices());
        };

        // Memaksa load voices di beberapa browser
        window.speechSynthesis.getVoices();
    }
});

// Tombol "Panggil" pertama kali
$(document).on('click', '.call-btn', function() {
    const antrianId = $(this).data('id');
    const row = $(this).closest('tr');
    const noAntrian = row.find('td:nth-child(2)').text();
    const namaPasien = row.find('td:nth-child(3)').text();
    const namaDokter = row.find('td:nth-child(5)').text() || 'Dokter';

    if (!confirm('Apakah Anda yakin ingin memanggil antrian ini?')) {
        return;
    }

    $.ajax({
        url: `/panggil-antrian/${antrianId}`,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                // Memanggil fungsi suara dengan nama pasien
                speakAntrian(noAntrian, namaPasien, namaDokter);
                alert(response.message);
                location.reload();
            } else {
                alert('Gagal memanggil antrian: ' + response.message);
            }
        },
        error: function(xhr) {
            const errMsg = xhr.responseJSON?.message || xhr.statusText;
            alert('Terjadi kesalahan: ' + errMsg);
        }
    });
});

function kirimEmail(antrianId) {
    if (!confirm('Apakah Anda yakin ingin mengirim email notifikasi ke pasien?')) {
        return;
    }

    $.ajax({
        url: '/kirim-email/' + antrianId,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
            } else {
                alert('Gagal: ' + response.message);
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseJSON?.message || 'Server error');
        }
    });
}

// Fungsi untuk tombol "Ulangi Panggil"
function ulangiPanggil(antrianId) {
    const row = $(`button[onclick="ulangiPanggil(${antrianId})"]`).closest('tr');
    const noAntrian = row.find('td:nth-child(2)').text();
    const namaPasien = row.find('td:nth-child(3)').text();
    const namaDokter = row.find('td:nth-child(5)').text() || 'Dokter';

    if (!confirm('Apakah Anda yakin ingin mengulangi panggilan antrian ini?')) {
        return;
    }

    $.ajax({
        url: '/ulangi-panggil/' + antrianId,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Memanggil fungsi suara dengan nama pasien
                speakAntrian(noAntrian, namaPasien, namaDokter);

                // Update jumlah panggilan
                $('#call-count-' + antrianId).text(response.count);
                alert(response.message);
                location.reload();
            } else {
                alert(response.message);
                location.reload();
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat mengulangi panggilan');
        }
    });
}

function markAsNotPresent(antrianId) {
    if (!confirm('Apakah Anda yakin ingin menandai antrian ini sebagai tidak hadir?')) {
        return;
    }

    $.ajax({
        url: '/antrian/' + antrianId + '/not-present',
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert('Gagal: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat menandai tidak hadir');
        }
    });
}

function recallPending(antrianId) {
    const row = $(`button[onclick="recallPending(${antrianId})"]`).closest('tr');
    const noAntrian = row.find('td:nth-child(2)').text();
    const namaPasien = row.find('td:nth-child(3)').text();
    const namaDokter = row.find('td:nth-child(5)').text() || 'Dokter';

    if (!confirm('Apakah Anda yakin ingin memanggil ulang antrian ini?')) {
        return;
    }

    $.ajax({
        url: '/antrian/' + antrianId + '/recall-pending',
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Memanggil fungsi suara dengan nama pasien
                speakAntrian(noAntrian, namaPasien, namaDokter);
                alert(response.message);
                location.reload();
            } else {
                alert('Gagal: ' + response.message);
                if (response.redirect) {
                    location.reload();
                }
            }
        },
        error: function(xhr) {
            const errMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat memanggil ulang';
            alert(errMsg);
            console.error(xhr);
        }
    });
}

// Fungsi untuk update status menjadi selesai
function updateStatus(antrianId, status) {
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi selesai?')) {
        return;
    }

    $.ajax({
        url: '/update-status-antrian/' + antrianId,
        method: 'PUT',
        data: {
            status: status
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert('Gagal mengupdate status: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat mengupdate status');
        }
    });
}
</script>
@endpush