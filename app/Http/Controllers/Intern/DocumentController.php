<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\NotificationService;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.intern.documents.edit', [
            'intern' => $request->user()->intern,
        ]);
    }

    public function preview(Request $request, string $field): Response
    {
        $intern = $request->user()->intern;

        $documents = [
            'ktp' => [
                'path' => $intern->ktp_path,
                'title' => 'KTP',
            ],
            'student_card' => [
                'path' => $intern->student_card_path,
                'title' => 'Kartu Siswa/Mahasiswa',
            ],
            'bpjs' => [
                'path' => $intern->bpjs_path,
                'title' => 'BPJS Ketenagakerjaan',
            ],
            'recommendation_letter' => [
                'path' => $intern->recommendation_letter_path,
                'title' => 'Surat Pengantar',
            ],
        ];

        abort_unless(isset($documents[$field]), 404);

        $documentPath = $documents[$field]['path'];
        abort_unless($documentPath && Storage::disk('public')->exists($documentPath), 404);

        $disk = Storage::disk('public');
        $mimeType = $disk->mimeType($documentPath) ?: 'application/octet-stream';

        return $disk->response(
            $documentPath,
            basename($documentPath),
            ['Content-Type' => $mimeType]
        );
    }

    public function update(Request $request): RedirectResponse
    {
        $intern = $request->user()->intern;

        $rules = [
            'ktp' => [$intern->ktp_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'student_card' => [$intern->student_card_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bpjs' => [$intern->bpjs_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'recommendation_letter' => [$intern->recommendation_letter_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
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

        if ($request->hasFile('recommendation_letter')) {
            $updateData['recommendation_letter_path'] = $request->file('recommendation_letter')->store("intern-documents/{$intern->id}", 'public');
        }
        
        $wasCompleted = $intern->hasCompletedDocuments();
        $updateData['documents_completed_at'] = $intern->documents_completed_at ?? now();

        $intern->update($updateData);
        $intern->refreshOperationalStatus();

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
            if ($intern->fresh()->status === 'active') {
                return redirect()->route('dashboard')->with('status', 'Berkas berhasil diupload. Seluruh onboarding selesai dan akun Anda sekarang aktif.');
            }

            return redirect()->route('dashboard')->with('status', 'Berkas berhasil diupload. Lanjutkan proses onboarding hingga seluruh data lengkap.');
        }

        return redirect()->route('intern.documents.edit')->with('status', 'Berkas dokumen berhasil diperbarui.');
    }
}
