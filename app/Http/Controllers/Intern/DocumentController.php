<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\NotificationService;
use App\Models\User;

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

        $rules = [
            'ktp' => [$intern->ktp_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'student_card' => [$intern->student_card_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bpjs' => [$intern->bpjs_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];

        $request->validate($rules);

        $updateData = [];

        if ($request->hasFile('ktp')) {
            $updateData['ktp_path'] = $request->file('ktp')->store("intern-documents/{$intern->id}", 'public');
        }

        if ($request->hasFile('student_card')) {
            $updateData['student_card_path'] = $request->file('student_card')->store("intern-documents/{$intern->id}", 'public');
        }

        if ($request->hasFile('bpjs')) {
            $updateData['bpjs_path'] = $request->file('bpjs')->store("intern-documents/{$intern->id}", 'public');
        }
        
        $wasCompleted = $intern->hasCompletedDocuments();
        $updateData['documents_completed_at'] = $intern->documents_completed_at ?? now();

        $intern->update($updateData);

        // Notifikasi ke Admin
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            NotificationService::send(
                userId: $admin->id,
                title: 'Upload Dokumen Intern',
                body: $intern->user->name . ' baru saja mengunggah/memperbarui dokumen wajibnya.',
                type: 'info',
                icon: 'ri-file-upload-line'
            );
        }

        if (!$wasCompleted) {
            return redirect()->route('dashboard')->with('status', 'Berkas berhasil diupload! Data Anda akan direview oleh admin.');
        }

        return redirect()->route('intern.documents.edit')->with('status', 'Berkas dokumen berhasil diperbarui.');
    }
}
