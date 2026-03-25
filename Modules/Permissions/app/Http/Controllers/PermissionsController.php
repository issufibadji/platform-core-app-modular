<?php

namespace Modules\Permissions\Http\Controllers;

use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function index()
    {
        return view('permissions::index');
    }
}
