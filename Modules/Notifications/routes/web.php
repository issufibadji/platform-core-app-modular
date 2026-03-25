<?php

use Illuminate\Support\Facades\Route;
use Modules\Notifications\Http\Controllers\NotificationsController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/notifications')
    ->name('core.notifications.')
    ->group(function () {
        Route::get('/', [NotificationsController::class, 'index'])->name('index');
    });
