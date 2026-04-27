<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        return view('pages.feature', ['title' => 'Daftar Divisi']);
    }

    public function read_list()
    {
        return view('pages.feature', ['title' => 'Read List Divisi']);
    }

    public function create()
    {
        return view('pages.feature', ['title' => 'Tambah Divisi Baru']);
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Divisi berhasil disimpan');
    }

    public function edit($id)
    {
        return view('pages.feature', ['title' => 'Edit Divisi']);
    }

    public function update(Request $request, $id)
    {
        return back()->with('success', 'Divisi berhasil diperbarui');
    }

    public function destroy($id)
    {
        return back()->with('success', 'Divisi berhasil dihapus');
    }
}
