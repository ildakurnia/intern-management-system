<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\InstitutionService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly InstitutionService $institutionService,
    ) {
    }

    public function edit(Request $request): View
    {
        return view('pages.intern.profile.edit', [
            'intern' => $request->user()->intern->loadMissing('institutionReference'),
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
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'institution_manual_name' => ['required_without:institution_id', 'nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'major' => ['required', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'school_grade' => ['nullable', 'string', 'max:50'],
            'semester' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];

        if ($intern->type === 'siswa') {
            $rules['school_grade'] = ['required', 'string', 'max:50'];
        }

        if ($intern->type === 'mahasiswa') {
            $rules['semester'] = ['required', 'string', 'max:50'];

            if ($this->institutionService->requiresBankAccount(
                $intern->type,
                $request->input('institution_id')
            )) {
                $rules['bank_account_number'] = ['required', 'string', 'max:50'];
            }
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store("intern-photos/{$intern->id}", 'public');
        }

        $wasCompleted = $intern->hasCompletedProfile();
        $validated['profile_completed_at'] = $intern->profile_completed_at ?? now();
        $validated = array_merge(
            $validated,
            $this->institutionService->resolveSelection(
                $request->input('institution_id'),
                $request->input('institution_manual_name')
            )
        );

        $validated['bank_account_number'] = $this->institutionService->requiresBankAccount(
            $intern->type,
            $validated['institution_id'] ?? null
        )
            ? ($validated['bank_account_number'] ?? null)
            : null;

        $intern->update($validated);
        $intern->refreshOperationalStatus();

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

        if (!$wasCompleted) {
            return redirect()
                ->route('intern.documents.edit')
                ->with('status', 'Profil berhasil dilengkapi. Sekarang upload berkas wajib.');
        }

        return redirect()
            ->route('intern.profile.edit')
            ->with('status', 'Data profil berhasil diperbarui.');
    }
}
