@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex">
        <div class="container-fluid p-4">
            <h3 class="mb-4">Dashboard</h3>
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card poli-card poli-kia">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">POLI KIA/KB</h5>
                                <i class="bi bi-people-fill fs-2"></i>
                            </div>
                            <p class="fs-4 fw-bold">{{ $queueCounts[5]->total ?? 0 }} Orang</p>
                            <p>Jumlah Antrian</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card poli-card poli-gigi">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>POLI GIGI</h5>
                                <i class="bi bi-people-fill fs-2"></i>
                            </div>
                            <p class="fs-4 fw-bold">{{ $queueCounts[4]->total ?? 0 }} Orang</p>
                            <p>Jumlah Antrian</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card poli-card poli-umum">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>POLI UMUM</h5>
                                <i class="bi bi-people-fill fs-2"></i>
                            </div>
                            <p class="fs-4 fw-bold">{{ $queueCounts[3]->total ?? 0 }} Orang</p>
                            <p>Jumlah Antrian</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card poli-card poli-vaksinasi">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">POLI VAKSINASI</h5>
                                <i class="bi bi-people-fill fs-2"></i>
                            </div>
                            <p class="fs-4 fw-bold">{{ $queueCounts[5]->total ?? 0 }} Orang</p>
                            <p>Jumlah Antrian</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card poli-card poli-kandungan">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">POLI KANDUNGAN</h5>
                                <i class="bi bi-people-fill fs-2"></i>
                            </div>
                            <p class="fs-4 fw-bold">{{ $queueCounts[5]->total ?? 0 }} Orang</p>
                            <p>Jumlah Antrian</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
