<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.intern.profile.edit', [
            'intern' => $request->user()->intern,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $intern = $request->user()->intern;

        $rules = [
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'],
            'institution' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'school_grade' => ['nullable', 'string', 'max:50'],
            'semester' => ['nullable', 'string', 'max:50'],
            'gpa' => ['nullable', 'numeric', 'between:0,4'],
            'photo' => ['nullable', 'image', 'max:2048'],
            // Documents
            'ktp' => [$intern->ktp_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'student_card' => [$intern->student_card_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'bpjs' => [$intern->bpjs_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'recommendation_letter' => [$intern->recommendation_letter_path ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];

        if ($intern->type === 'siswa') {
            $rules['school_grade'] = ['required', 'string', 'max:50'];
        }

        if ($intern->type === 'mahasiswa') {
            $rules['semester'] = ['required', 'string', 'max:50'];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store("intern-photos/{$intern->id}", 'public');
        }

        if ($request->hasFile('ktp')) {
            $validated['ktp_path'] = $request->file('ktp')->store("intern-documents/{$intern->id}", 'public');
        }

        if ($request->hasFile('student_card')) {
            $validated['student_card_path'] = $request->file('student_card')->store("intern-documents/{$intern->id}", 'public');
        }

        if ($request->hasFile('bpjs')) {
            $validated['bpjs_path'] = $request->file('bpjs')->store("intern-documents/{$intern->id}", 'public');
        }

        if ($request->hasFile('recommendation_letter')) {
            $validated['recommendation_letter_path'] = $request->file('recommendation_letter')->store("intern-documents/{$intern->id}", 'public');
        }

        $wasCompletedProfile = $intern->hasCompletedProfile();
        $wasCompletedDocs = $intern->hasCompletedDocuments();
        $validated['profile_completed_at'] = $intern->profile_completed_at ?? now();

        $intern->update($validated);
        $intern->refreshDocumentCompletion();

        // Notifikasi ke Admin
        $admins = User::role(['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            NotificationService::send(
                userId: $admin->id,
                title: 'Update Profil Intern',
                body: $intern->user->name . ' baru saja memperbarui data profilnya.',
                type: 'info',
                icon: 'ri-user-follow-line'
            );
        }

        if (!$wasCompletedDocs || !$wasCompletedProfile) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'Profil dan berkas berhasil dilengkapi. Data Anda akan direview oleh admin.');
        }

        return redirect()
            ->route('intern.profile.edit')
            ->with('status', 'Data profil dan berkas berhasil diperbarui.');
    }
}
