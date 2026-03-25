<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Settings\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings::index');
    }

    public function edit(Setting $setting)
    {
        return view('settings::edit', compact('setting'));
    }
}
