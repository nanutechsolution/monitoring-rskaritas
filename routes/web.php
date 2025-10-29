<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Icu\PatientHistory;
use App\Livewire\PatientList;
use App\Livewire\PatientMonitor;
use App\Livewire\Picu\PatientHistory as PicuPatientHistory;
use App\Livewire\Picu\Workspace;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('dashboard', PatientList::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/icu/history/{noRawat}', PatientHistory::class)
    ->middleware('auth')
    ->name('monitoring.icu.history');
Route::get('/icu/workspace/{noRawat}/{sheetDate?}', App\Livewire\Icu\Workspace::class)
    ->middleware('auth')
    ->name('monitoring.icu.workspace');
Route::get('monitoring/{no_rawat}', PatientMonitor::class)
    ->middleware(['auth'])
    ->name('monitoring.nicu');
Route::get('monitoring/{no_rawat}/report/{cycle_id}/report/pdf', [ReportController::class, 'generateReportPdf'])
    ->middleware(['auth'])
    ->name('monitoring.report.pdf');

Route::get('/icu/workspace/{noRawat}/{sheetDate}/print', [ReportController::class, 'printPdf'])
    ->middleware('auth')
    ->name('monitoring.icu.print');


// 1. Halaman Riwayat Pasien PICU (Pintu Gerbang)
Route::get('/picu/history/{noRawat}', PicuPatientHistory::class)
    ->middleware('auth')
    ->name('monitoring.picu.history');

// 2. Halaman Lembar Kerja PICU (Input & Laporan)
Route::get('/picu/workspace/{noRawat}/{sheetDate?}', Workspace::class)
    ->middleware('auth')
    ->name('monitoring.picu.workspace');

Route::get('/picu/print/{monitoringSheet}', [ReportController::class, 'printPICU'])
    ->middleware('auth')
    ->name('monitoring.picu.print');

require __DIR__ . '/auth.php';
