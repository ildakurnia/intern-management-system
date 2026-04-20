<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(): View
    {
        return view('pages.feature', ['title' => 'Monitoring Logbook Intern']);
    }

    public function show(string $logbook): View
    {
        return view('pages.feature', ['title' => 'Detail Logbook Intern']);
    }
}
