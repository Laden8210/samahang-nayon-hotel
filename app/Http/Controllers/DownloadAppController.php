<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DownloadAppController extends Controller
{
    public function index()
    {
        return view('download-app');
    }
}
