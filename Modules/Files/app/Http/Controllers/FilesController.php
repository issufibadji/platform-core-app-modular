<?php

namespace Modules\Files\Http\Controllers;

use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    public function index()
    {
        return view('files::index');
    }

    public function upload()
    {
        return view('files::upload');
    }
}
