<?php

use Illuminate\Support\Facades\Route;
use Modules\FeatureFlags\Http\Controllers\FeatureFlagsController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/feature-flags')
    ->name('core.featureflags.')
    ->group(function () {
        Route::get('/',       [FeatureFlagsController::class, 'index'])->name('index');
        Route::get('/create', [FeatureFlagsController::class, 'create'])->name('create');
    });
