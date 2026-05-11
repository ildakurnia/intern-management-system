<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Division\StoreDivisionRequest;
use App\Http\Requests\Admin\Division\UpdateDivisionRequest;
use App\Models\Division;
use App\Services\DivisionService;

class DivisionController extends Controller
{
    protected $divisionService;

    public function __construct(DivisionService $divisionService)
    {
        $this->divisionService = $divisionService;
    }

    public function index()
    {
        $divisions = $this->divisionService->getAllDivisions();
        return view('pages.admin.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('pages.admin.divisions.create');
    }

    public function store(StoreDivisionRequest $request)
    {
        $this->divisionService->createDivision($request->validated());

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil dibuat!');
    }

    public function edit(Division $division)
    {
        return view('pages.admin.divisions.edit', compact('division'));
    }

    public function update(UpdateDivisionRequest $request, Division $division)
    {
        $this->divisionService->updateDivision($division, $request->validated());

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy(Division $division)
    {
        if (!$this->divisionService->deleteDivision($division)) {
            return back()->with('error', 'Divisi tidak bisa dihapus karena masih memiliki anggota!');
        }

        return redirect()->route('admin.divisions.index')->with('success', 'Divisi berhasil dihapus!');
    }
}
