<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/settings')
    ->name('core.settings.')
    ->group(function () {
        Route::get('/',             [SettingsController::class, 'index'])->name('index');
        Route::get('/{setting}',    [SettingsController::class, 'edit'])->name('edit');
    });
