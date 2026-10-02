<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    // Menampilkan pengumuman umum dan pengumuman khusus departemen karyawan
    public function index(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        $query = Announcement::with(['user', 'department'])
                    ->where('is_active', true)
                    ->latest();
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        } else {
            $query->where(function ($q) use ($employee) {
                $q->whereNull('department_id');
                if ($employee && $employee->department_id) {
                    $q->orWhere('department_id', $employee->department_id);
                }
            });
        }
        
        $announcements = $query->paginate(10);
        $departments = Department::all();
        
        return view('user.announcements.index', compact('announcements', 'departments'));
    }

    // Detail pengumuman (hanya pengumuman aktif)
    public function show(Announcement $announcement)
    {
        if (!$announcement->is_active) {
            abort(404);
        }
        
        return view('user.announcements.show', compact('announcement'));
    }
}
