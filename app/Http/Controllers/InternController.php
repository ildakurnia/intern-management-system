<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InternController extends Controller
{
    public function index()
    {
        return view('pages.feature', ['title' => 'Data Intern List']);
    }

    public function read_list()
    {
        return view('pages.feature', ['title' => 'Read List Intern']);
    }

    public function create()
    {
        return view('pages.feature', ['title' => 'Tambah Intern Baru']);
    }

    public function store(Request $request)
    {
        return back()->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        return view('pages.feature', ['title' => 'Edit Data Intern']);
    }

    public function update(Request $request, $id)
    {
        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        return back()->with('success', 'Data berhasil dihapus');
    }

    public function daily_log()
    {
        return view('pages.feature', ['title' => 'Daily Log Aktivitas']);
    }

    public function tasks()
    {
        return view('pages.feature', ['title' => 'Daftar Tasks / Jobdesk']);
    }
}
