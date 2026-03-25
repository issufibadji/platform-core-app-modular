<?php

use Illuminate\Support\Facades\Route;
use Modules\Files\Http\Controllers\FilesController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/files')
    ->name('core.files.')
    ->group(function () {
        Route::get('/',       [FilesController::class, 'index'])->name('index');
        Route::get('/upload', [FilesController::class, 'upload'])->name('upload');
    });
