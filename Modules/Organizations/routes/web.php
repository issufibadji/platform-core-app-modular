<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\OrganizationsController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/organizations')
    ->name('core.organizations.')
    ->group(function () {
        Route::get('/', [OrganizationsController::class, 'index'])->name('index');
        Route::get('/create', [OrganizationsController::class, 'create'])->name('create');
        Route::get('/{organization}/edit', [OrganizationsController::class, 'edit'])->name('edit');
    });
