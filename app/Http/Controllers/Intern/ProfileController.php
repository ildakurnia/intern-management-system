<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
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

        $validated['profile_completed_at'] = $intern->profile_completed_at ?? now();

        $intern->update($validated);

        return redirect()
            ->route('intern.documents.edit')
            ->with('status', 'Profil berhasil dilengkapi. Sekarang upload berkas wajib.');
    }
}
