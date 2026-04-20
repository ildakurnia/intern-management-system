<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(): View
    {
        return view('pages.feature', ['title' => 'Logbook Intern Bimbingan']);
    }

    public function show(string $logbook): View
    {
        return view('pages.feature', ['title' => 'Detail Logbook Intern']);
    }
}
