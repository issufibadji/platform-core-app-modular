<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        return view('users::index');
    }

    public function create()
    {
        return view('users::create');
    }

    public function edit(User $user)
    {
        return view('users::edit', compact('user'));
    }
}
