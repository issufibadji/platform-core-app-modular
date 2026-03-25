<?php

use Illuminate\Support\Facades\Route;
use Modules\Permissions\Http\Controllers\PermissionsController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/permissions')
    ->name('core.permissions.')
    ->group(function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('index');
    });
