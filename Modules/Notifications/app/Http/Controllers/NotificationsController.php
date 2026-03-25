<?php

namespace Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    public function index()
    {
        return view('notifications::index');
    }
}
