<?php

use App\Http\Controllers\ReportController;
use App\Livewire\PatientList;
use App\Livewire\PatientMonitor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('dashboard', PatientList::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('monitoring/{no_rawat}', PatientMonitor::class)
    ->middleware(['auth'])
    ->name('monitoring.nicu');
Route::get('monitoring/{no_rawat}/report/{cycle_id}/report/pdf', [ReportController::class, 'generateReportPdf'])
    ->middleware(['auth'])
    ->name('monitoring.report.pdf');


require __DIR__ . '/auth.php';
