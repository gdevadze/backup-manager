<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\BackupServerController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('servers.index'));

Route::resource('servers', BackupServerController::class)->parameters(['servers' => 'server']);

Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
Route::post('/servers/{server}/run', [BackupController::class, 'runNow'])->name('backups.run');
Route::get('/backups/{backup}', [BackupController::class, 'show'])->name('backups.show');
Route::get('/backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
