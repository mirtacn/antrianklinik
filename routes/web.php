<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatapasienController;
use App\Http\Controllers\PasienUmumController;
use App\Http\Controllers\PasienBPJSController;
use App\Http\Controllers\DatapoliController;
use App\Http\Controllers\DatalayananController;
use App\Http\Controllers\DatadokterController;
use App\Http\Controllers\PanggilController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DatajadwaldokterController;
use App\Models\Antrian;
use Illuminate\Support\Facades\Route;

Route::get('/get-doctors', [AntrianController::class, 'getDoctors'])->name('get.doctors');
// ---Role Admin---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


        // PASIEN UMUM

    Route::get('/pasienumum', [PasienUmumController::class, 'index'])->name('pasienumum');
    Route::get('/pasienumum/tambah', [PasienUmumController::class, 'create'])->name('tambahpasienumum');
    Route::get('/pasienumum/edit/{id}', [PasienUmumController::class, 'edit'])->name('editpasienumum');
    Route::post('/pasienumum/simpan', [PasienUmumController::class, 'store'])->name('simpandatapasienumum');
    Route::put('/pasienumum/update/{id}', [PasienUmumController::class, 'update'])->name('updatepasienumum');
    Route::delete('/pasienumum/delete/{id}', [PasienUmumController::class, 'destroy'])->name('deletepasienumum');

    // PASIEN BPJS
    Route::get('/pasienbpjs', [PasienBpjsController::class, 'index'])->name('pasienbpjs');
    Route::get('/pasienbpjs/tambah', [PasienBpjsController::class, 'create'])->name('tambahpasienbpjs');
    Route::get('/pasienbpjs/edit/{id}', [PasienBpjsController::class, 'edit'])->name('editpasienbpjs');
    Route::post('/pasienbpjs/simpan', [PasienBpjsController::class, 'store'])->name('simpanpasienbpjs');
    Route::put('/pasienbpjs/update/{id}', [PasienBpjsController::class, 'update'])->name('updatepasienbpjs');
    Route::delete('/pasienbpjs/delete/{id}', [PasienBpjsController::class, 'destroy'])->name('deletepasienbpjs');

    // Data Pasien
    Route::get('/datapasien', [DatapasienController::class, 'index'])->name('pasien');

    // Data Poli
    Route::prefix('datapoli')->group(function () {
        Route::get('/', [DatapoliController::class, 'index'])->name('poli');
        Route::get('/tambah', [DatapoliController::class, 'add'])->name('tambahpoli');
        Route::post('/simpan', [DatapoliController::class, 'store'])->name('simpandatapoli');
        Route::get('/edit/{id}', [DatapoliController::class, 'edit'])->name('editpoli');
        Route::put('/update/{id}', [DatapoliController::class, 'update'])->name('updatepoli');
        Route::delete('/delete/{id}', [DatapoliController::class, 'destroy'])->name('deletepoli');
    });

    // Data Layanan
    Route::prefix('datalayanan')->group(function () {
        Route::get('/', [DatalayananController::class, 'index'])->name('layanan');
        Route::get('/tambah', [DatalayananController::class, 'add'])->name('tambahlayanan');
        Route::post('/simpan', [DatalayananController::class, 'store'])->name('simpandatalayanan');
        Route::get('/edit/{id}', [DatalayananController::class, 'edit'])->name('editlayanan');
        Route::put('/update/{id}', [DatalayananController::class, 'update'])->name('updatelayanan');
        Route::delete('/delete/{id}', [DatalayananController::class, 'destroy'])->name('deletelayanan');
    });

    // Data Dokter
    Route::prefix('datadokter')->group(function () {
        Route::get('/', [DatadokterController::class, 'index'])->name('dokter');
        Route::get('/tambah', [DatadokterController::class, 'add'])->name('tambahdokter');
        Route::post('/simpan', [DatadokterController::class, 'store'])->name('simpandatadokter');
        Route::get('/edit/{id}', [DatadokterController::class, 'edit'])->name('editdokter');
        Route::put('/update/{id}', [DatadokterController::class, 'update'])->name('updatedokter');
        Route::delete('/delete/{id}', [DatadokterController::class, 'destroy'])->name('deletedokter');
    });

    // Data Jadwal Dokter
    Route::prefix('datajadwaldokter')->group(function () {
        Route::get('/', [DatajadwaldokterController::class, 'index'])->name('jadwaldokter');
        Route::get('/', [DatajadwaldokterController::class, 'index'])->name('jadwaldokter');
        Route::get('/tambah', [DatajadwaldokterController::class, 'add'])->name('tambahjadwaldokter');
        Route::post('/simpan', [DatajadwaldokterController::class, 'store'])->name('simpanjadwaldokter');
        Route::get('/edit/{id}', [DatajadwaldokterController::class, 'edit'])->name('editjadwaldokter');
        Route::put('/update/{id}', [DatajadwaldokterController::class, 'update'])->name('updatejadwaldokter');
        Route::delete('/delete/{id}', [DatajadwaldokterController::class, 'destroy'])->name('deletejadwaldokter');
    });

    // Panggilan
    Route::get('/panggil', [PanggilController::class, 'index'])->name('panggil');
    Route::put('/panggil-antrian/{id}', [PanggilController::class, 'panggil'])->name('antrian.panggil');
    Route::put('/ulangi-panggil/{id}', [PanggilController::class, 'ulangiPanggil'])->name('ulangi.panggil');
    Route::put('/update-status-antrian/{antrian}', [PanggilController::class, 'updateStatusSelesai'])->name('antrian.updateStatus');
    Route::get('/current-calls', [PanggilController::class, 'getCurrentCalls']);
    Route::get('/report', [PanggilController::class, 'reportantrian']);
    Route::get('/report-table', [PanggilController::class, 'showReportTable'])->name('report.table');
    Route::get('/export-excel', [PanggilController::class, 'exportToExcel'])->name('export.excel');
    Route::get('/export-pdf', [PanggilController::class, 'exportToPDF'])->name('export.pdf');
    Route::get('/datajadwaldokter/get-poli-by-doctor/{doctorId}', [DatajadwaldokterController::class, 'getPoliByDoctor'])->name('get.poli.by.doctor');
    Route::put('/antrian/{id}/not-present', [PanggilController::class, 'markAsNotPresent']);
    Route::put('/antrian/{id}/recall-pending', [PanggilController::class, 'recallPending']);
    Route::post('/kirim-email/{id}', [PanggilController::class, 'kirimEmail']);
});

// ---Role User---
Route::get('/', [AntrianController::class, 'index'])->name('index');
Route::get('/monitor', [AntrianController::class, 'monitor'])->name('monitor');
Route::get('/struk', [AntrianController::class, 'struk'])->name('struk');
Route::get('/pesan', [AntrianController::class, 'pesan'])->name('pesan');
// Route::get('/cari-pasien', [PasienUmumController::class, 'cariPasien']);
Route::get('/cari-pasien', [AntrianController::class, 'cariPasien']);
Route::post('/pesan', [AntrianController::class, 'simpan'])->name('simpan');
Route::get('/struk/download', [AntrianController::class, 'downloadStruk'])->name('download.struk');
Route::get('/struk/cetak', [AntrianController::class, 'cetakStruk'])->name('cetak.struk');

