<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // Menampilkan daftar pengumuman, bisa filter per departemen
    public function index(Request $request)
    {
        $query = Announcement::with(['user', 'department'])->latest();
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $announcements = $query->paginate(10);
        $departments = Department::all();
        
        return view('superadmin.announcements.index', compact('announcements', 'departments'));
    }

    // Menampilkan form buat pengumuman baru
    public function create()
    {
        $departments = Department::all();
        return view('superadmin.announcements.create', compact('departments'));
    }

    // Simpan pengumuman baru (maks 10 gambar, 2MB/gambar)
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

        // Upload gambar jika ada
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('announcements', 'public');
            }
            $data['images'] = $imagePaths;
        }

        $announcement = Announcement::create($data);

        // Kirim notifikasi ke user terkait jika pengumuman aktif
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

        return redirect()->route('superadmin.announcements.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    // Menampilkan detail pengumuman
    public function show(Announcement $announcement)
    {
        return view('superadmin.announcements.show', compact('announcement'));
    }

    // Menampilkan form edit pengumuman
    public function edit(Announcement $announcement)
    {
        $departments = Department::all();
        return view('superadmin.announcements.edit', compact('announcement', 'departments'));
    }

    // Update pengumuman (hapus gambar lama jika ada gambar baru)
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

        // Ganti gambar — hapus yang lama, upload yang baru
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

        return redirect()->route('superadmin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // Hapus pengumuman beserta gambar dari storage
    public function destroy(Announcement $announcement)
    {
        if ($announcement->images) {
            foreach ($announcement->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $announcement->delete();

        return redirect()->route('superadmin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
