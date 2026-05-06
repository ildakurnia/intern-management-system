<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\NotificationService;

class InternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        // For admin, view all interns. For mentor, view only their division.
        // We'll use the scopeForMentor from the model.
        
        $interns = Intern::with(['user', 'division'])
            ->forMentor($request->user())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('major', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.interns.index', compact('interns', 'search'));
    }

    public function show(Intern $intern): View
    {
        // Eager load relations
        $intern->load(['user', 'division', 'logbooks', 'mentor']);

        return view('admin.interns.show', compact('intern'));
    }

    public function edit(Intern $intern): View
    {
        $mentors = User::role('mentor')->get();
        $divisions = Division::where('is_active', true)->get();
        
        return view('admin.interns.edit', compact('intern', 'mentors', 'divisions'));
    }

    public function update(Request $request, Intern $intern): RedirectResponse
    {
        $request->validate([
            'mentor_id' => ['nullable', 'exists:users,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
        ]);

        $intern->update([
            'mentor_id' => $request->mentor_id,
            'division_id' => $request->division_id,
        ]);

        // If intern has a user account, sync division there too
        if ($intern->user_id) {
            $intern->user->update(['division_id' => $request->division_id]);
        }

        return redirect()->route('admin.interns.show', $intern)
            ->with('success', 'Data intern berhasil diperbarui.');
    }

    /**
     * Approve intern registration
     */
    public function approve(Intern $intern): RedirectResponse
    {
        $intern->update([
            'registration_status' => 'approved',
            'status' => 'active',
        ]);

        if ($intern->user_id) {
            NotificationService::send(
                userId: $intern->user_id,
                title: 'Pendaftaran Disetujui',
                body: 'Selamat! Akun magang Anda telah disetujui oleh Admin. Anda sekarang dapat mengisi logbook harian.',
                type: 'success',
                icon: 'ri-check-double-line'
            );
        }

        return back()->with('status', 'Intern berhasil disetujui dan diaktifkan.');
    }

    // Stubs for the import functionalities
    public function import(): View
    {
        return view('admin.interns.import'); // Or just redirect with message
    }

    public function template()
    {
        return back()->with('status', 'Template export not yet implemented.');
    }

    public function storeImport(Request $request): RedirectResponse
    {
        return back()->with('status', 'Import functionality not yet implemented.');
    }
}
