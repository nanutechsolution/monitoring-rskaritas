<?php

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
    ->name('patient.monitor')
    ->where('no_rawat', '\d{4}/\d{2}/\d{2}/\d+');
Route::get('monitoring/{no_rawat}/report/{cycle_id}', [PatientMonitor::class, 'generateReportPdf'])
    ->middleware(['auth'])
    ->name('patient.monitor.report');


require __DIR__ . '/auth.php';
