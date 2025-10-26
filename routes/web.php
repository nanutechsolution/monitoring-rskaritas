<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Icu\MonitorSheet;
use App\Livewire\Icu\ObservationGrid;
use App\Livewire\Icu\PatientHistory;
use App\Livewire\PatientList;
use App\Livewire\PatientMonitor;
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


require __DIR__ . '/auth.php';
