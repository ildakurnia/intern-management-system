<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\View\View;

class InternDocumentController extends Controller
{
    public function index(): View
    {
        $interns = Intern::query()
            ->with('division')
            ->latest()
            ->paginate(15);

        return view('pages.admin.intern-documents.index', compact('interns'));
    }
}
