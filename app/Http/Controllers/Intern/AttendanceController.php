<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkin()
    {
        return view('pages.feature', ['title' => 'Check In / Out Peserta']);
    }

    public function monitoring()
    {
        return view('pages.feature', ['title' => 'Monitoring Kehadiran']);
    }
}
