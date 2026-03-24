<?php

use Illuminate\Support\Facades\Route;
use Modules\Organizations\Http\Controllers\OrganizationsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('organizations', OrganizationsController::class)->names('organizations');
});
