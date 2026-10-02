<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // Menampilkan daftar pengumuman (Admin)
    public function index(Request $request)
    {
        $query = Announcement::with(['user', 'department'])->latest();
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $announcements = $query->paginate(10);
        $departments = Department::all();
        
        return view('admin.announcements.index', compact('announcements', 'departments'));
    }

    // Form tambah pengumuman baru
    public function create()
    {
        $departments = Department::all();
        return view('admin.announcements.create', compact('departments'));
    }

    // Simpan pengumuman baru & kirim notifikasi
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'content', 'department_id']);
        $data['user_id'] = Auth::id();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('announcements', 'public');
            }
            $data['images'] = $imagePaths;
        }

        $announcement = Announcement::create($data);

        if ($announcement->is_active) {
            $users = \App\Models\User::query();
            if ($announcement->department_id) {
                $users->whereHas('employee', function($q) use ($announcement) {
                    $q->where('department_id', $announcement->department_id);
                });
            }
            $users = $users->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\AnnouncementNotification($announcement));
            }
        }

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    // Detail pengumuman
    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    // Form edit pengumuman
    public function edit(Announcement $announcement)
    {
        $departments = Department::all();
        return view('admin.announcements.edit', compact('announcement', 'departments'));
    }

    // Update pengumuman & kelola gambar
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'content', 'department_id']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('images')) {
            if ($announcement->images) {
                foreach ($announcement->images as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('announcements', 'public');
            }
            $data['images'] = $imagePaths;
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // Hapus pengumuman
    public function destroy(Announcement $announcement)
    {
        if ($announcement->images) {
            foreach ($announcement->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
