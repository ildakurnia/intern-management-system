<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('permissions')->orderBy('order')->get();
        return view('pages.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('pages.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'route_name' => 'nullable|string|max:255',
        ]);

        Menu::create($validated);

        return redirect()->route('menus.index')->with('status', 'Menu berhasil dibuat.');
    }

    public function edit(Menu $menu)
    {
        return view('pages.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'route_name' => 'nullable|string|max:255',
        ]);

        $menu->update($validated);

        return redirect()->route('menus.index')->with('status', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('status', 'Menu berhasil dihapus.');
    }
}
