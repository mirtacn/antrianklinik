@extends('layoutuser.app')

@section('title', 'Pesan')

@section('content')
    <section id="pesan" class="py-5">
        <div class="container">
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form class="mt-4 form-container bg-white p-4 rounded shadow" method="POST" action="{{ route('simpan') }}">
                @csrf
                <h1 class="form-title">Pendaftaran Antrian Online</h1>

                <!-- Layanan -->
                <div class="mb-3">
                    <label for="layanan" class="form-label">Pilih Layanan :</label>
                    <select class="form-select @error('id_layanan') is-invalid @enderror" id="layanan" name="id_layanan"
                        required>
                        <option value="">Pilih Layanan</option>
                        @foreach ($layanan as $item)
                            <option value="{{ $item->id }}" {{ old('id_layanan') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- NIK -->
                <div class="mb-3" id="nik-container">
                    <label for="no_ktp" class="form-label">NIK :</label>
                    <input type="text" class="form-control @error('nik') is-invalid @enderror" id="no_ktp"
                        name="nik" placeholder="Isi NIK Anda (16 Digit)" required value="{{ old('nik') }}">
                    <small id="status-nik" class="text-danger"></small>
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- BPJS -->
                <div class="mb-3" id="bpjs-container" style="display: none;">
                    <label for="nomor_bpjs" class="form-label">Nomor BPJS :</label>
                    <input type="text" class="form-control @error('nomor_bpjs') is-invalid @enderror" id="nomor_bpjs"
                        name="nomor_bpjs" placeholder="Isi Nomor BPJS Anda (13 Digit)" value="{{ old('nomor_bpjs') }}">
                    <small id="status-bpjs" class="text-danger"></small>
                    @error('nomor_bpjs')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap :</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama_lengkap"
                        name="nama" placeholder="Isi nama lengkap Anda" required readonly
                        style="background-color: #f0f0f0;" value="{{ old('nama') }}">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- No Telepon -->
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No Telepon :</label>
                    <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" id="no_telepon"
                        name="no_telepon" placeholder="Isi nomor telepon aktif yang terhubung dengan WhatsApp" required
                        value="{{ old('no_telepon') }}">
                    @error('no_telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email :</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="Isi email yang aktif" required value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Poli -->
                <div class="mb-3">
                    <label for="poli" class="form-label">Pilih Poli :</label>
                    <select class="form-select @error('id_poli') is-invalid @enderror" id="poli" name="id_poli"
                        required>
                        <option value="">Pilih Poli</option>
                        @foreach ($poli as $item)
                            <option value="{{ $item->id }}" {{ old('id_poli') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_poli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tanggal Daftar -->
                <div class="mb-3">
                    <label for="tanggal_daftar" class="form-label">Pilih Tanggal Daftar :</label>
                    <select class="form-select @error('tanggal_antrian') is-invalid @enderror" id="tanggal_daftar"
                        name="tanggal_antrian" required>
                        <option value="">Pilih Tanggal</option>
                        <option value="{{ $hariIni }}" {{ old('tanggal_antrian') == $hariIni ? 'selected' : '' }}>
                            Hari Ini ({{ \Carbon\Carbon::today()->format('d-m-Y') }})
                        </option>
                        <option value="{{ $besok }}" {{ old('tanggal_antrian') == $besok ? 'selected' : '' }}>
                            Besok ({{ \Carbon\Carbon::tomorrow()->format('d-m-Y') }})
                        </option>
                    </select>
                    @error('tanggal_antrian')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jadwal Dokter -->
                <div class="mb-3">
                    <h5 class="mb-3">Pilih Jadwal Dokter:</h5>
                    <div class="row" id="jadwal-dokter-container">
                        @if (old('id_jadwal'))
                            <!-- Jika ada data lama, tampilkan jadwal yang sesuai -->
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Jadwal Tersedia</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="id_jadwal"
                                                id="jadwal-{{ old('id_jadwal') }}" value="{{ old('id_jadwal') }}"
                                                checked>
                                            <label class="form-check-label" for="jadwal-{{ old('id_jadwal') }}">
                                                {{ \App\Models\JadwalDokter::find(old('id_jadwal'))->dokter->nama_dokter }}
                                                -
                                                {{ \App\Models\JadwalDokter::find(old('id_jadwal'))->jam_mulai }} s/d
                                                {{ \App\Models\JadwalDokter::find(old('id_jadwal'))->jam_selesai }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Jika tidak ada data lama -->
                            <div class="col-12 text-center">
                                <p class="text-muted">Silakan pilih poli dan tanggal terlebih dahulu</p>
                            </div>
                        @endif
                    </div>
                    @error('id_jadwal')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- <div class="mb-3">
                    <h5 class="mb-3">Pilih Jadwal Dokter:</h5>
                    <div class="row" id="jadwal-dokter-container">
                        @foreach ($dokter as $jadwal)
                            <div class="col-md-4">
                                <label
                                    class="card p-3 mb-3 cursor-pointer {{ $jadwal->status == 'tutup' ? 'bg-light text-muted' : '' }}"
                                    style="border: 1px solid #ccc; position: relative;">
                                    <input type="radio" name="id_jadwal" value="{{ $jadwal->id }}"
                                        class="form-check-input" style="position: absolute; top: 10px; right: 10px;"
                                        {{ $jadwal->status == 'tutup' ? 'disabled' : '' }}>
                                    <div class="d-flex align-items-center mb-2">
                                        @if ($jadwal->dokter->foto_profil)
                                            <img src="{{ Storage::url($jadwal->dokter->foto_profil) }}" alt="Foto Dokter"
                                                width="80" height="80"
                                                style="object-fit: cover; border-radius: 50%; margin-right: 15px;">
                                        @else
                                            <div
                                                style="width: 80px; height: 80px; border-radius: 50%; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <i class="fas fa-user" style="color: #aaa;"></i>
                                            </div>
                                        @endif
                                        <h6 class="mb-0">{{ $jadwal->dokter->nama }}</h6>
                                    </div>
                                    <p class="mb-1"><strong>Jam:</strong> {{ $jadwal->jam_mulai }} -
                                        {{ $jadwal->jam_selesai }}</p>
                                    <p class="mb-1"><strong>Status:</strong>
                                        <span class="badge {{ $jadwal->status == 'buka' ? 'bg-success' : 'bg-danger' }}">
                                            {{ strtoupper($jadwal->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-1"><strong>Antrian Diambil:</strong> {{ $jadwal->jumlah_antrian }}</p>
                                    <p class="mb-0"><strong>Sisa Antrian:</strong> {{ $jadwal->sisa_antrian }}</p>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div> --}}
                <button type="submit" class="w-100 btn btn-lg"
                    style="background-color: #EC744A; color: white; border-radius: 30px; font-weight: bold;">
                    Submit
                </button>
            </form>
        </div>
    </section>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Show/hide NIK/BPJS fields based on service type
                $('#layanan').on('change', function() {
                    var selectedLayanan = $(this).find('option:selected').text().toLowerCase();

                    if (selectedLayanan.includes('bpjs')) {
                        $('#nik-container').hide();
                        $('#no_ktp').removeAttr('required');
                        $('#bpjs-container').show();
                        $('#nomor_bpjs').attr('required', 'required');
                    } else {
                        $('#bpjs-container').hide();
                        $('#nomor_bpjs').removeAttr('required');
                        $('#nik-container').show();
                        $('#no_ktp').attr('required', 'required');
                    }
                });

                // Check NIK
                $('#no_ktp').on('keyup', function() {
                    var nik = $(this).val();
                    if (nik.length === 16) {
                        $('#status-nik').text('Mencari data...');
                        checkPatientData({
                            nik: nik
                        }, '#status-nik', '#nama_lengkap');
                    }
                });

                // Check BPJS
                $('#nomor_bpjs').on('keyup', function() {
                    var bpjs = $(this).val();
                    if (bpjs.length === 13) {
                        $('#status-bpjs').text('Mencari data...');
                        checkPatientData({
                            nomor_bpjs: bpjs
                        }, '#status-bpjs', '#nama_lengkap');
                    }
                });

                function checkPatientData(data, statusSelector, nameField) {
                    $.ajax({
                        url: '/cari-pasien',
                        method: 'GET',
                        data: data,
                        success: function(response) {
                            if (response.status === 'found') {
                                $(nameField).val(response.data.nama);
                                $(statusSelector).html('<span class="text-success">Data ditemukan</span>');
                                // Auto-fill other fields if needed
                                $('#no_telepon').val(response.data.no_telepon);
                                $('#email').val(response.data.email);
                            } else {
                                $(nameField).val('');
                                $(statusSelector).html(
                                    '<span class="text-danger">Data tidak ditemukan. Silahkan kunjungi klinik untuk daftarkan data diri Anda!</span>'
                                );
                            }
                        },
                        error: function() {
                            $(nameField).val('');
                            $(statusSelector).html('<span class="text-danger">Gagal mengambil data</span>');
                        }
                    });
                }


            });
        </script>

        {{-- <script>
            $(document).ready(function() {
                $('#poli').on('change', function() {
                    var poliId = $(this).val();
                    $('#jadwal-dokter-container').html('<p class="text-center">Loading...</p>');

                    if (poliId) {
                        $.ajax({
                            url: '/get-doctors/' + poliId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#jadwal-dokter-container').empty();

                                let found = false;
                                let now = new Date();
                                let currentHours = now.getHours();
                                let currentMinutes = now.getMinutes();

                                $.each(data, function(key, doctor) {
                                    if (doctor.jadwal_dokter && doctor.jadwal_dokter
                                        .length > 0) {
                                        $.each(doctor.jadwal_dokter, function(index,
                                            schedule) {
                                            let endParts = schedule.jam_selesai
                                                .split(':');
                                            let endHours = parseInt(endParts[0]);
                                            let endMinutes = parseInt(endParts[1]);

                                            let isClosed = (currentHours >
                                                endHours || (currentHours ===
                                                    endHours &&
                                                    currentMinutes >= endMinutes
                                                    ));

                                            if (schedule.kuotasisa > 0 && !
                                                isClosed) {
                                                found = true;

                                                let statusBadge =
                                                    '<span class="badge bg-success">BUKA</span>';

                                                $('#jadwal-dokter-container')
                                                    .append(`
                                        <div class="col-md-4">
                                            <label class="card p-3 mb-3 cursor-pointer" style="border: 1px solid #ccc; position: relative;">
                                                <input type="radio" name="id_jadwal" value="${schedule.id}" class="form-check-input" style="position: absolute; top: 10px; right: 10px;">
                                                <div class="d-flex align-items-center mb-2">
                                                    ${doctor.foto_profil ?
                                                        `<img src="/storage/${doctor.foto_profil}" alt="Foto Dokter" width="80" height="80" style="object-fit: cover; border-radius: 50%; margin-right: 15px;">` :
                                                            `<div style="width: 80px; height: 80px; border-radius: 50%; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                                        <i class="fas fa-user" style="font-size: 30px; color: #aaa;"></i>
                                                                </div>`
                                                    }
                                                                                                     <div class="flex-grow-1">
                                                        <h6 class="mb-2" style="font-size: 1.1rem;">${doctor.nama_dokter}</h6>
                                                        <div class="d-flex flex-column">
                                                            <span class="mb-1"><strong>Jam:</strong> ${schedule.jam_mulai} - ${schedule.jam_selesai}</span>
                                                            <span class="mb-1"><strong>Status:</strong> ${statusBadge}</span>
                                                            <span class="mb-1"><strong>Antrian Diambil:</strong> ${schedule.kuotadiambil}</span>
                                                            <span class="mb-0"><strong>Sisa Antrian:</strong> ${schedule.kuotasisa}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </label>
                                        </div>
                                    `);
                                            }
                                        });
                                    }
                                });

                                if (!found) {
                                    $('#jadwal-dokter-container').html(
                                        '<div class="col-12 text-center"><p class="text-danger">Tidak ada jadwal tersedia</p></div>'
                                        );
                                }
                            },
                            error: function() {
                                $('#jadwal-dokter-container').html(
                                    '<p class="text-danger">Terjadi kesalahan saat memuat jadwal.</p>'
                                    );
                            }
                        });
                    } else {
                        $('#jadwal-dokter-container').html(
                            '<p class="text-muted">Silakan pilih poli terlebih dahulu</p>');
                    }
                });
            });
        </script> --}}
        <script>
            $(document).ready(function() {
                // Function to map day names
                function getDayName(dateStr) {
                    const date = new Date(dateStr);
                    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    return days[date.getDay()];
                }

                // Load schedules when poli or date changes
                $('#poli, #tanggal_daftar').on('change', function() {
                    var poliId = $('#poli').val();
                    var tanggal = $('#tanggal_daftar').val();

                    if (!poliId || !tanggal) {
                        $('#jadwal-dokter-container').html(
                            '<div class="col-12 text-center"><p class="text-muted">Silakan pilih poli dan tanggal terlebih dahulu</p></div>'
                        );
                        return;
                    }

                    $('#jadwal-dokter-container').html('<p class="text-center">Memuat jadwal...</p>');

                    // Get the day name for the selected date
                    var hari = getDayName(tanggal);

                    $.ajax({
                        url: '/get-doctors',
                        type: 'GET',
                        data: {
                            poli_id: poliId,
                            hari: hari,
                            tanggal: tanggal
                        },
                        success: function(data) {
                            $('#jadwal-dokter-container').empty();

                            if (data.length === 0) {
                                $('#jadwal-dokter-container').html(
                                    '<div class="col-12 text-center"><p class="text-danger">Tidak ada jadwal tersedia untuk hari ini</p></div>'
                                );
                                return;
                            }

                            $.each(data, function(index, schedule) {
                                let statusClass = schedule.kuotasisa > 0 ? 'bg-success' :
                                    'bg-danger';
                                let statusText = schedule.kuotasisa > 0 ? 'BUKA' : 'TUTUP';
                                let disabledAttr = schedule.kuotasisa > 0 ? '' : 'disabled';

                                $('#jadwal-dokter-container').append(`
                        <div class="col-md-4 mb-3">
                            <label class="card p-3 cursor-pointer ${schedule.kuotasisa <= 0 ? 'bg-light text-muted' : ''}"
                                   style="border: 1px solid #ccc; position: relative;">
                                <input type="radio" name="id_jadwal" value="${schedule.id}"
                                       class="form-check-input" style="position: absolute; top: 10px; right: 10px;"
                                       ${disabledAttr} required>
                                <div class="d-flex align-items-center mb-2">
                                    ${schedule.dokter.foto_profil ?
                                        `<img src="/storage/${schedule.dokter.foto_profil}" alt="Foto Dokter" width="80" height="80"
                                                                      style="object-fit: cover; border-radius: 50%; margin-right: 15px;">` :
                                        `<div style="width: 80px; height: 80px; border-radius: 50%; background-color: #f0f0f0;
                                                                    display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                                    <i class="fas fa-user" style="color: #aaa;"></i>
                                                                 </div>`}
                                    <h6 class="mb-0">${schedule.dokter.nama_dokter}</h6>
                                </div>
                                <p class="mb-1"><strong>Hari:</strong> ${schedule.hari}</p>
                                <p class="mb-1"><strong>Jam:</strong> ${schedule.jam_mulai} - ${schedule.jam_selesai}</p>
                                <p class="mb-1"><strong>Status:</strong>
                                    <span class="badge ${statusClass}">${statusText}</span>
                                </p>
                                <p class="mb-1"><strong>Kuota Tersedia:</strong> ${schedule.kuotasisa}</p>
                            </label>
                        </div>
                    `);
                            });
                        },
                        error: function() {
                            $('#jadwal-dokter-container').html(
                                '<div class="col-12 text-center"><p class="text-danger">Gagal memuat jadwal dokter</p></div>'
                            );
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
