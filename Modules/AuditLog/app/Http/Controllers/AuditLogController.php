<?php

namespace Modules\AuditLog\Http\Controllers;

use App\Http\Controllers\Controller;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('auditlog::index');
    }
}
