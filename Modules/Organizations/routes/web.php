<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Livewire\CreateOrganization;
use Modules\Organizations\Http\Livewire\ListOrganizations;

Route::middleware(['auth', 'verified'])->prefix('core/organizations')->name('core.organizations.')->group(function () {
    Route::get('/', ListOrganizations::class)->name('index');
    Route::get('/create', CreateOrganization::class)->name('create');
    Route::get('/{organization}/edit', CreateOrganization::class)->name('edit');
});
