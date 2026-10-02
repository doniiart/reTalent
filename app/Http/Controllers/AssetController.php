<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Simpan data aset kantor baru milik pengguna
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        auth()->user()->assets()->create($request->all());

        return back()->with('success', 'Aset kantor berhasil ditambahkan.');
    }

    // Update data aset kantor
    public function update(Request $request, \App\Models\Asset $asset)
    {
        if ($asset->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $asset->update($request->all());

        return back()->with('success', 'Aset kantor berhasil diperbarui.');
    }

    // Hapus data aset kantor
    public function destroy(\App\Models\Asset $asset)
    {
        if ($asset->user_id !== auth()->id()) {
            abort(403);
        }

        $asset->delete();

        return back()->with('success', 'Aset kantor berhasil dihapus.');
    }
}
