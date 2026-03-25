<?php

namespace Modules\FeatureFlags\Http\Controllers;

use Illuminate\Routing\Controller;

class FeatureFlagsController extends Controller
{
    public function index()
    {
        return view('featureflags::index');
    }

    public function create()
    {
        return view('featureflags::create');
    }
}
