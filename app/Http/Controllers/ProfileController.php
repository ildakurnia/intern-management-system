<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile based on their role.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Cek peran untuk menyiapkan data yang relevan
        $roleName = ucfirst($user->getRoleNames()->first() ?? 'User');
        
        $viewData = [
            'user' => $user,
            'roleName' => $roleName,
        ];

        // Custom data based on role
        if ($user->hasRole('intern')) {
            $intern = $user->intern;
            $viewData['intern'] = $intern;
            $viewData['logbookCount'] = $intern ? $intern->logbooks()->count() : 0;
            $viewData['divisionName'] = $intern && $intern->division ? $intern->division->name : 'Belum Ditentukan';
            $viewData['startDate'] = $intern && $intern->start_date ? $intern->start_date->format('d M Y') : '-';
            $viewData['endDate'] = $intern && $intern->end_date ? $intern->end_date->format('d M Y') : '-';
            $viewData['status'] = $intern ? ucfirst($intern->status) : '-';
        } elseif ($user->hasRole('mentor')) {
            $viewData['divisionName'] = $user->division ? $user->division->name : 'Semua Divisi';
            $viewData['internsCount'] = $user->division ? $user->division->interns()->count() : 0;
        } else {
            // Admin atau Superadmin
            $viewData['divisionName'] = 'Manajemen Sistem';
        }

        return view('pages.profile.index', $viewData);
    }
}
