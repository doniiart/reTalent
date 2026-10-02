<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\JobType;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Halaman kelola data master (Departemen, Jabatan, Jenis Pekerjaan)
    public function index()
    {
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        return view('admin.settings.index', compact('departments', 'positions', 'jobTypes'));
    }

    // Kelola Departemen (tambah & hapus)
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function destroyDepartment(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('admin.settings.index')->with('error', 'Departemen tidak dapat dihapus karena masih memiliki karyawan.');
        }
        
        $department->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Departemen berhasil dihapus.');
    }

    // Kelola Jabatan (tambah & hapus)
    public function storePosition(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Position::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function destroyPosition(Position $position)
    {
        if ($position->employees()->count() > 0) {
            return redirect()->route('admin.settings.index')->with('error', 'Jabatan tidak dapat dihapus karena masih memiliki karyawan.');
        }
        
        $position->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Jabatan berhasil dihapus.');
    }

    // Kelola Jenis Pekerjaan (tambah & hapus)
    public function storeJobType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        JobType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Jenis Pekerjaan berhasil ditambahkan.');
    }

    public function destroyJobType(JobType $jobType)
    {
        if ($jobType->employees()->count() > 0) {
            return redirect()->route('admin.settings.index')->with('error', 'Jenis Pekerjaan tidak dapat dihapus karena masih memiliki karyawan.');
        }
        
        $jobType->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Jenis Pekerjaan berhasil dihapus.');
    }
}
