<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['auth', 'verified'])
    ->prefix('core/users')
    ->name('core.users.')
    ->group(function () {
        Route::get('/', [UsersController::class, 'index'])->name('index');
        Route::get('/create', [UsersController::class, 'create'])->name('create');
        Route::get('/{user}/edit', [UsersController::class, 'edit'])->name('edit');
    });
