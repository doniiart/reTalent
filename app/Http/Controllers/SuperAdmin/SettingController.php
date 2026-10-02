<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Position;
use App\Models\JobType;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Menampilkan halaman kelola data master (Departemen, Jabatan, Jenis Pekerjaan)
    public function index()
    {
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        return view('superadmin.settings.index', compact('departments', 'positions', 'jobTypes'));
    }

    // Tambah & hapus Departemen
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->route('superadmin.settings.index')->with('success', 'Departemen berhasil ditambahkan.')->with('active_tab', 'master');
    }

    public function destroyDepartment(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('superadmin.settings.index')->with('error', 'Departemen tidak dapat dihapus karena masih memiliki karyawan.')->with('active_tab', 'master');
        }
        
        $department->delete();
        return redirect()->route('superadmin.settings.index')->with('success', 'Departemen berhasil dihapus.')->with('active_tab', 'master');
    }

    // Tambah & hapus Jabatan (Position)
    public function storePosition(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Position::create([
            'name' => $request->name,
        ]);

        return redirect()->route('superadmin.settings.index')->with('success', 'Jabatan berhasil ditambahkan.')->with('active_tab', 'master');
    }

    public function destroyPosition(Position $position)
    {
        if ($position->employees()->count() > 0) {
            return redirect()->route('superadmin.settings.index')->with('error', 'Jabatan tidak dapat dihapus karena masih memiliki karyawan.')->with('active_tab', 'master');
        }
        
        $position->delete();
        return redirect()->route('superadmin.settings.index')->with('success', 'Jabatan berhasil dihapus.')->with('active_tab', 'master');
    }

    // Tambah & hapus Jenis Pekerjaan (JobType)
    public function storeJobType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        JobType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('superadmin.settings.index')->with('success', 'Jenis Pekerjaan berhasil ditambahkan.')->with('active_tab', 'master');
    }

    public function destroyJobType(JobType $jobType)
    {
        if ($jobType->employees()->count() > 0) {
            return redirect()->route('superadmin.settings.index')->with('error', 'Jenis Pekerjaan tidak dapat dihapus karena masih memiliki karyawan.')->with('active_tab', 'master');
        }
        
        $jobType->delete();
        return redirect()->route('superadmin.settings.index')->with('success', 'Jenis Pekerjaan berhasil dihapus.')->with('active_tab', 'master');
    }
}
