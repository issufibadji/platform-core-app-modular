<?php

namespace Modules\Organizations\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Organizations\Models\Organization;

class OrganizationsController extends Controller
{
    public function index()
    {
        return view('organizations::index');
    }

    public function create()
    {
        return view('organizations::create');
    }

    public function edit(Organization $organization)
    {
        return view('organizations::edit', compact('organization'));
    }
}
