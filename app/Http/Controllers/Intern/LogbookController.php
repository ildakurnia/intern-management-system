<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(Request $request): View
    {
        $intern = $request->user()->intern;

        abort_if(! $intern, 403, 'Akun intern belum terhubung dengan data magang.');

        return view('pages.intern.logbooks.index', [
            'intern' => $intern,
            'logbooks' => $intern->logbooks()
                ->latest('tanggal')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('pages.intern.logbooks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $intern = $request->user()->intern;

        abort_if(! $intern, 403, 'Akun intern belum terhubung dengan data magang.');

        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('logbooks', 'tanggal')->where('intern_id', $intern->id),
            ],
            'uraian_aktivitas' => ['required', 'string', 'min:100'],
            'pembelajaran_diperoleh' => ['required', 'string', 'min:100'],
            'kendala_dialami' => ['nullable', 'string'],
            'confirmation' => ['accepted'],
        ]);

        $intern->logbooks()->create([
            'tanggal' => $validated['tanggal'],
            'uraian_aktivitas' => $validated['uraian_aktivitas'],
            'pembelajaran_diperoleh' => $validated['pembelajaran_diperoleh'],
            'kendala_dialami' => $validated['kendala_dialami'] ?? null,
        ]);

        return redirect()
            ->route('intern.logbooks.index')
            ->with('status', 'Logbook berhasil disimpan.');
    }

    public function show(Request $request, Logbook $logbook): View
    {
        $intern = $request->user()->intern;

        abort_if(! $intern || $logbook->intern_id !== $intern->id, 403, 'Logbook ini bukan milik akun Anda.');

        return view('pages.intern.logbooks.show', [
            'logbook' => $logbook,
        ]);
    }

    public function edit(Request $request, Logbook $logbook): View
    {
        $intern = $request->user()->intern;

        abort_if(! $intern || $logbook->intern_id !== $intern->id, 403, 'Logbook ini bukan milik akun Anda.');

        return view('pages.intern.logbooks.edit', [
            'logbook' => $logbook,
        ]);
    }

    public function update(Request $request, Logbook $logbook): RedirectResponse
    {
        $intern = $request->user()->intern;

        abort_if(! $intern || $logbook->intern_id !== $intern->id, 403, 'Logbook ini bukan milik akun Anda.');

        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('logbooks', 'tanggal')
                    ->where('intern_id', $intern->id)
                    ->ignore($logbook->id),
            ],
            'uraian_aktivitas' => ['required', 'string', 'min:100'],
            'pembelajaran_diperoleh' => ['required', 'string', 'min:100'],
            'kendala_dialami' => ['nullable', 'string'],
            'confirmation' => ['accepted'],
        ]);

        $logbook->update([
            'tanggal' => $validated['tanggal'],
            'uraian_aktivitas' => $validated['uraian_aktivitas'],
            'pembelajaran_diperoleh' => $validated['pembelajaran_diperoleh'],
            'kendala_dialami' => $validated['kendala_dialami'] ?? null,
        ]);

        return redirect()
            ->route('intern.logbooks.show', $logbook)
            ->with('status', 'Logbook berhasil diperbarui.');
    }

    public function destroy(Request $request, Logbook $logbook): RedirectResponse
    {
        $intern = $request->user()->intern;

        abort_if(! $intern || $logbook->intern_id !== $intern->id, 403, 'Logbook ini bukan milik akun Anda.');

        $logbook->delete();

        return redirect()
            ->route('intern.logbooks.index')
            ->with('status', 'Logbook berhasil dihapus.');
    }
}
