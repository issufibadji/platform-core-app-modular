<?php

use Illuminate\Support\Facades\Route;
use Modules\AuditLog\Http\Controllers\AuditLogController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/auditlog')
    ->name('core.auditlog.')
    ->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
    });
