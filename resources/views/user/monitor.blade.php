@extends('layoutuser.app')

@section('title', 'Monitor')

@section('content')
    <section id="antrian" class="py-4">
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col-12">
                    <div class="row" id="doctorQueues">
                        <div class="text-center text-white">
                            <h3>Memuat data antrian</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-3 text-center" style="margin-top:90px;">
        <marquee direction='left'>
            Selamat datang di sistem antrian online Klinik Mabarrot Hasyimiyah Manyar Gresik.
            Untuk informasi lebih lanjut hubungi CS: <strong>031-399-28461</strong>
        </marquee>
    </footer>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateMonitorDisplay() {
            $.get('/current-calls', function(data) {
                const container = $('#doctorQueues');
                container.empty();

                if (data.length === 0) {
                    container.html(`
                        <div class="col-12">
                            <div class="card antrian-box">
                                <div class="card-header">
                                    Tidak Ada Jadwal Dokter Saat Ini
                                </div>
                                <div class="card-body">
                                    <p>Tidak ada dokter yang memiliki jadwal pada hari dan waktu saat ini.</p>
                                </div>
                            </div>
                        </div>
                    `);
                    return;
                }

                // Calculate responsive column size based on number of doctors
                let colClass;
                if (data.length >= 6) {
                    colClass = 'col-xl-2 col-lg-3 col-md-4 col-sm-6';
                } else if (data.length >= 4) {
                    colClass = 'col-xl-3 col-lg-4 col-md-6';
                } else {
                    colClass = 'col-xl-4 col-lg-6 col-md-6';
                }

                data.forEach(function(queue) {
                    // Get patient name for current call
                    const currentCallName = queue.current_call ? queue.current_call.nama : '-';

                    // Format waiting list items
                    let waitingListItems = '';
                    if (queue.waiting_list.length > 0) {
                        queue.waiting_list.slice(0, 3).forEach(item => {
                            const patientName = item.nama ||
                                              (item.pasienumum ? item.pasienumum.nama : null) ||
                                              (item.pasienbpjs ? item.pasienbpjs.nama : null) ||
                                              '-';
                            waitingListItems += `<li class="text-truncate">${queue.poli_kode}${item.no_antrian} - ${patientName}</li>`;
                        });
                    } else {
                        waitingListItems = '<li>-</li>';
                    }

                    if (queue.waiting_list.length > 3) {
                        waitingListItems += `<li class="text-muted">+ ${queue.waiting_list.length - 3} lagi</li>`;
                    }

                    const card = $(`
                        <div class="${colClass} mb-3">
                            <div class="card antrian-box h-100">
                                <div class="card-header py-2">
                                    <h5 class="mb-0"> ${queue.doctor_name}</h5>
                                    <small class="text-center text-white">${queue.poli_name}</small>
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="text-muted mb-1">Sedang Dipanggil:</h6>
                                    ${queue.current_call ? `
                                        <h2 class="nomor-antrian mb-1">${queue.poli_kode}${queue.current_call.no_antrian}</h2>
                                        <div class="nama-pasien mb-2">${currentCallName}</div>
                                    ` : `
                                        <h2 class="nomor-antrian mb-1">0</h2>
                                        <div class="nama-pasien mb-2"></div>
                                    `}
                                    <hr class="my-2">
                                    <h6 class="text-muted mb-1">Antrian Selanjutnya:</h6>
                                    <ul class="waiting-list list-unstyled mb-0 small">
                                        ${waitingListItems}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);

                    container.append(card);
                });
            }).fail(function(error) {
                console.error('Gagal mengambil data antrian:', error);
                $('#doctorQueues').html(`
                    <div class="col-12">
                        <div class="alert alert-danger">
                            Gagal memuat data antrian. Silakan refresh halaman.
                        </div>
                    </div>
                `);
            });
        }

        updateMonitorDisplay();
        setInterval(updateMonitorDisplay, 3000);

        $(window).focus(updateMonitorDisplay);
    </script>
@endpush
