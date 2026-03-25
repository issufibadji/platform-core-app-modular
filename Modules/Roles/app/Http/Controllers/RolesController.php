<?php

namespace Modules\Roles\Http\Controllers;

use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index()
    {
        return view('roles::index');
    }

    public function create()
    {
        return view('roles::create');
    }
}
