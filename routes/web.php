<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\BackupJobController;
use App\Http\Controllers\BackupServerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('servers', BackupServerController::class);

Route::get('servers/{server}/jobs', [BackupJobController::class, 'index'])
    ->name('servers.jobs');

Route::post('servers/{server}/jobs', [BackupJobController::class, 'store'])
    ->name('servers.jobs.store');

Route::post('jobs/{job}/toggle', [BackupJobController::class, 'toggle'])
    ->name('jobs.toggle');

Route::delete('jobs/{job}', [BackupJobController::class, 'destroy'])
    ->name('jobs.destroy');

Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
Route::get('backups/{backup}', [BackupController::class, 'show'])->name('backups.show');
