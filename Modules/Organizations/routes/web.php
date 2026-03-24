<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Livewire\OrganizationCreate;
use Modules\Organizations\Http\Livewire\OrganizationIndex;

Route::middleware(['auth', 'verified'])
    ->prefix('core/organizations')
    ->name('core.organizations.')
    ->group(function () {
        Route::get('/', OrganizationIndex::class)->name('index');
        Route::get('/create', OrganizationCreate::class)->name('create');
    });
