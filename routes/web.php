<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Icu\PatientHistory;
use App\Livewire\Monitoring\AnesthesiaCreate;
use App\Livewire\Monitoring\AnesthesiaHistory;
use App\Livewire\Monitoring\AnesthesiaShow;
use App\Livewire\PatientList;
use App\Livewire\PatientMonitor;
use App\Livewire\Picu\PicuPatientMonitor;
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

Route::get('monitoring/nicu/{no_rawat}', PatientMonitor::class)
    ->middleware(['auth'])
    ->name('monitoring.nicu');
Route::get('monitoring/picu/{no_rawat}', PicuPatientMonitor::class)
    ->middleware(['auth'])
    ->name('monitoring.picu');
Route::get('monitoring/nicu/{no_rawat}/report/{cycle_id}/report/pdf', [ReportController::class, 'generateReportPdf'])
    ->middleware(['auth'])
    ->name('monitoring.report.pdf');
Route::get('monitoring/picu/{no_rawat}/report/{cycle_id}/report/pdf', [ReportController::class, 'generateReportPdf'])
    ->middleware(['auth'])
    ->name('monitoring.picu.report.pdf');
Route::get('/icu/workspace/{noRawat}/{sheetDate}/print', [ReportController::class, 'printPdf'])
    ->middleware('auth')
    ->name('monitoring.icu.print');
Route::get('monitoring/anestesi/history/{noRawat}', AnesthesiaHistory::class)
    ->name('monitoring.anestesi.history');
Route::get('monitoring/anestesi/create/{noRawat}', AnesthesiaCreate::class)
    ->name('monitoring.anestesi.create');
Route::get('monitoring/anestesi/edit/{monitoringId}', AnesthesiaCreate::class)
    ->name('monitoring.anestesi.edit');
Route::get('monitoring/anestesi/show/{monitoringId}', action: AnesthesiaShow::class)
    ->name('monitoring.anestesi.show');
Route::get('monitoring/anestesi/print/{monitoringId}', [ReportController::class, 'printAnesthesia'])
    ->name('monitoring.anestesi.print');

require __DIR__ . '/auth.php';
