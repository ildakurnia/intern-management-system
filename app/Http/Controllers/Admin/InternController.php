<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InternImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Services\InternImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InternController extends Controller
{
    public function index(): View
    {
        $interns = Intern::query()
            ->with('division')
            ->latest()
            ->paginate(15);

        return view('pages.admin.interns.index', compact('interns'));
    }

    public function show(Intern $intern): View
    {
        $intern->load('division', 'user');

        return view('pages.admin.interns.show', compact('intern'));
    }

    public function import(): View
    {
        return view('pages.admin.interns.import');
    }

    public function template(): BinaryFileResponse
    {
        return Excel::download(
            new InternImportTemplateExport(),
            'template-import-intern.xlsx',
        );
    }

    public function storeImport(Request $request, InternImportService $importService): RedirectResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv,txt', 'max:5120'],
        ]);

        try {
            $result = $importService->import($validated['file']);
        } catch (\Throwable $exception) {
            return back()->withErrors(['file' => $exception->getMessage()]);
        }

        return redirect()
            ->route('admin.interns.index')
            ->with('status', "Import selesai. {$result['imported']} data masuk, {$result['skipped']} dilewati.")
            ->with('import_errors', $result['errors']);
    }
}
