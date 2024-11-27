<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function index()
    {
        return view('admin.system-log.index');
    }
}
