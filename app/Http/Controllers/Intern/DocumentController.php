<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.intern.documents.edit', [
            'intern' => $request->user()->intern,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $intern = $request->user()->intern;

        $validated = $request->validate([
            'ktp' => [$intern->ktp_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'student_card' => [$intern->student_card_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bpjs' => [$intern->bpjs_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $paths = [];

        foreach (['ktp' => 'ktp_path', 'student_card' => 'student_card_path', 'bpjs' => 'bpjs_path'] as $input => $column) {
            if ($request->hasFile($input)) {
                $paths[$column] = $request->file($input)->store("intern-documents/{$intern->id}", 'public');
            }
        }

        if ($paths !== []) {
            $intern->update($paths);
        }

        $intern->refreshDocumentCompletion();

        return redirect()
            ->route($intern->hasCompletedDocuments() ? 'dashboard' : 'intern.documents.edit')
            ->with('status', $intern->hasCompletedDocuments()
                ? 'Berkas wajib sudah lengkap. Kamu bisa masuk dashboard.'
                : 'Berkas tersimpan. Lengkapi semua berkas wajib untuk lanjut.');
    }
}
