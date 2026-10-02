<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // Menampilkan daftar karyawan dengan filter & statistik
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Employee::with(['user', 'department', 'position', 'jobType']);
        
        // Filter berdasarkan departemen & status
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }
        
        $totalEmployees = Employee::count();

        // Jika bukan admin/superadmin, tampilkan view direktori karyawan
        if (!auth()->user()->hasRole(['superadmin', 'admin'])) {
            $employees = $query->paginate(12)->withQueryString();
            $departments = Department::all();
            
            $totalDepartments = Department::count();
            $topDepartments = Department::withCount('employees')
                                ->orderByDesc('employees_count')
                                ->take(3)
                                ->get();
                                
            return view('superadmin.employees.directory', compact(
                'employees', 'departments', 'totalEmployees', 'totalDepartments', 'topDepartments'
            ));
        }

        // View admin/superadmin — dengan statistik kehadiran & karyawan baru
        $perPage = (int) $request->input('per_page', 10);
        $employees = $query->paginate($perPage)->withQueryString();

        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        $allEmployeesList = Employee::with(['user', 'department'])->orderBy('created_at', 'desc')->get();

        $activeEmployees = Employee::whereIn('employment_status', ['tetap', 'kontrak'])->count();
        $probationEmployees = Employee::where('employment_status', 'intership')->count();
        
        // Hitung persentase kehadiran hari ini
        $todayAttendance = \App\Models\Attendance::whereDate('date', today())->whereIn('status', ['present', 'late'])->count();
        $attendancePercentage = $totalEmployees > 0 ? round(($todayAttendance / $totalEmployees) * 100, 1) : 0;

        $newEmployeesThisMonth = Employee::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count();
                                        
        $recentAttendees = \App\Models\Attendance::with('user')
                                ->whereDate('date', today())
                                ->whereIn('status', ['present', 'late'])
                                ->take(3)
                                ->get();

        return view('superadmin.employees.index', compact(
            'employees', 'departments', 'positions', 'jobTypes', 'allEmployeesList',
            'totalEmployees', 'activeEmployees', 'probationEmployees', 'attendancePercentage',
            'newEmployeesThisMonth', 'todayAttendance', 'recentAttendees'
        ));
    }

    // Menampilkan form tambah karyawan baru
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        return view('superadmin.employees.create', compact('departments', 'positions', 'jobTypes'));
    }

    // Simpan karyawan baru (user + employee + payroll awal)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'department_id' => 'required',
            'position_id' => 'required',
            'job_type_id' => 'required',
            'join_date' => 'required|date',
            'employment_status' => 'required',
            'role' => 'required'
        ]);

        DB::transaction(function() use ($request) {
            // Buat akun user dengan password default '12345'
            $user = User::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'password' => Hash::make('12345'),
            ]);

            $user->assignRole($request->role);

            // Upload foto jika ada
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
                $user->update(['profile_photo_path' => $photoPath]);
            }
            
            // Data gaji & tunjangan (hanya superadmin yang bisa set)
            $basicSalary = 0;
            $premium = 0;
            $tunjangan_teknis = 0;
            $tunjangan_laptop = 0;
            $tunjangan_keluarga = 0;
            $reward_disiplin = 0;
            $reward_solat = 0;

            if (auth()->user()->hasRole('superadmin')) {
                $basicSalary = $request->basic_salary ?? 0;
                $premium = $request->premium ?? 0;
                $tunjangan_teknis = $request->tunjangan_teknis ?? 0;
                $tunjangan_laptop = $request->tunjangan_laptop ?? 0;
                $tunjangan_keluarga = $request->tunjangan_keluarga ?? 0;
                $reward_disiplin = $request->reward_disiplin ?? 0;
                $reward_solat = $request->reward_solat ?? 0;
            }
            
            // Simpan data employee
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'job_type_id' => $request->job_type_id,
                'photo' => $photoPath,
                'phone' => $request->phone,
                'address' => $request->address,
                'last_education' => $request->last_education,
                'work_location' => $request->work_location,
                'employment_status' => $request->employment_status,
                'join_date' => $request->join_date,
                'basic_salary' => $basicSalary,
                'premium' => $premium,
                'tech_allowance' => $tunjangan_teknis,
                'laptop_allowance' => $tunjangan_laptop,
                'family_allowance' => $tunjangan_keluarga,
                'discipline_reward' => $reward_disiplin,
                'prayer_reward' => $reward_solat,
            ]);

            // Buat draft payroll bulan ini (hitung gaji kotor, JHT 2%, gaji bersih)
            $totalAllowance = $tunjangan_teknis + $tunjangan_laptop + $tunjangan_keluarga;
            $totalReward = $reward_disiplin + $reward_solat;
            $grossSalary = $basicSalary + $premium + $totalAllowance + $totalReward;
            $jhtDeduction = $grossSalary * 0.02;
            $netSalary = $grossSalary - $jhtDeduction;

            \App\Models\Payroll::create([
                'user_id' => $user->id,
                'month' => now()->format('F'),
                'year' => now()->year,
                'basic_salary' => $basicSalary,
                'premium' => $premium,
                'total_allowance' => $totalAllowance,
                'total_reward' => $totalReward,
                'jht_deduction' => $jhtDeduction,
                'net_salary' => $netSalary,
                'status' => 'draft',
            ]);
        });

        return redirect()->route('superadmin.employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    // Menampilkan detail karyawan
    public function show(string $id)
    {
        $employee = Employee::with(['user', 'department', 'position', 'jobType'])->findOrFail($id);
        return view('superadmin.employees.show', compact('employee'));
    }

    // Menampilkan form edit karyawan
    public function edit(string $id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        return view('superadmin.employees.edit', compact('employee', 'departments', 'positions', 'jobTypes'));
    }

    // Update data karyawan (user + employee)
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->user_id,
            'department_id' => 'required',
            'position_id' => 'required',
            'job_type_id' => 'required',
            'join_date' => 'required|date',
            'employment_status' => 'required'
        ]);

        DB::transaction(function() use ($request, $employee) {
            // Update data user (nama & email)
            $user = $employee->user;
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Sync role jika diubah
            if ($request->has('role')) {
                $user->syncRoles([$request->role]);
            }

            // Ganti foto — hapus yang lama jika ada foto baru
            $photoPath = $employee->photo;
            if ($request->hasFile('photo')) {
                if ($photoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('photos', 'public');
                $user->update(['profile_photo_path' => $photoPath]);
            }
            
            $employeeData = [
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'job_type_id' => $request->job_type_id,
                'photo' => $photoPath,
                'phone' => $request->phone,
                'address' => $request->address,
                'last_education' => $request->last_education,
                'work_location' => $request->work_location,
                'employment_status' => $request->employment_status,
                'join_date' => $request->join_date,
            ];

            // Data gaji & tunjangan hanya bisa diubah superadmin
            if (auth()->user()->hasRole('superadmin')) {
                $employeeData['basic_salary'] = $request->basic_salary ?? 0;
                $employeeData['premium'] = $request->premium ?? 0;
                $employeeData['tech_allowance'] = $request->tunjangan_teknis ?? 0;
                $employeeData['laptop_allowance'] = $request->tunjangan_laptop ?? 0;
                $employeeData['family_allowance'] = $request->tunjangan_keluarga ?? 0;
                $employeeData['discipline_reward'] = $request->reward_disiplin ?? 0;
                $employeeData['prayer_reward'] = $request->reward_solat ?? 0;
                $employeeData['jht'] = $request->jht ?? 0;
            }

            $employee->update($employeeData);
        });

        return redirect()->route('superadmin.employees.index')->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    // Hapus karyawan beserta akun user & foto
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;
        
        // Hapus foto dari storage jika ada
        if ($employee->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->photo);
        }
        
        $employee->delete();
        $user->delete();

        return redirect()->route('superadmin.employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    // Export data seluruh karyawan ke Excel atau PDF
    public function export(Request $request)
    {
        $departmentId = $request->query('department_id');
        $positionId = $request->query('position_id');
        $status = $request->query('status');
        $format = $request->query('format', 'excel');

        $fileNameBase = 'Laporan_Data_Karyawan_' . now()->format('Ymd');

        if ($format === 'pdf') {
            $query = Employee::with(['user', 'department', 'position', 'jobType']);

            if ($departmentId) {
                $query->where('department_id', $departmentId);
            }
            if ($positionId) {
                $query->where('position_id', $positionId);
            }
            if ($status) {
                $query->where('employment_status', $status);
            }

            $employees = $query->orderBy('created_at', 'desc')->get();
            $departmentName = $departmentId ? Department::find($departmentId)->name ?? null : null;
            
            $statusLabels = ['tetap' => 'Tetap', 'kontrak' => 'Kontrak', 'intership' => 'Magang'];
            $statusName = $status ? ($statusLabels[$status] ?? $status) : null;

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.employee_pdf', compact('employees', 'departmentName', 'statusName'))
                ->setPaper('a4', 'landscape');
            return $pdf->download($fileNameBase . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\EmployeeExport($departmentId, $positionId, $status),
            $fileNameBase . '.xlsx'
        );
    }

    // Download profil karyawan individu sebagai PDF
    public function downloadProfile(Employee $employee)
    {
        $employee->load(['user', 'department', 'position', 'jobType']);

        // Ambil riwayat absensi bulan ini
        $attendances = \App\Models\Attendance::where('user_id', $employee->user_id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date', 'asc')
            ->get();

        // Ambil 3 riwayat gaji terakhir
        $payrolls = \App\Models\Payroll::where('user_id', $employee->user_id)
            ->orderBy('year', 'desc')
            ->orderByRaw("FIELD(month, 'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January')")
            ->take(3)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.employee_individual_pdf', compact('employee', 'attendances', 'payrolls'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Profil_Karyawan_' . str_replace(' ', '_', $employee->user->name) . '.pdf');
    }

    // Import data karyawan dari file Excel/CSV
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt|max:10240',
        ], [
            'file.required' => 'Silakan pilih file Excel atau CSV terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file tidak boleh melebihi 10MB.'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\EmployeeImport, $request->file('file'));
            return redirect()->route('superadmin.employees.index')->with('success', 'Data karyawan baru dari file luar berhasil di-import.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal meng-import data karyawan: ' . $e->getMessage());
        }
    }

    // Unduh template Excel/CSV untuk import karyawan
    public function downloadTemplate()
    {
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=template_import_karyawan.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['nip', 'nama', 'email', 'departemen', 'jabatan', 'jenis_pekerjaan', 'no_telepon', 'alamat', 'pendidikan_terakhir', 'lokasi_kerja', 'status_karyawan', 'tanggal_bergabung', 'gaji_pokok', 'role'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            
            // Baris Contoh
            fputcsv($file, [
                '19920101202601',
                'Budi Santoso',
                'budi.santoso@example.com',
                'Teknologi Informasi',
                'Software Engineer',
                'Full Time',
                '081234567890',
                'Jl. Sudirman No. 12, Jakarta',
                'S1 Teknik Informatika',
                'Kantor Pusat',
                'tetap',
                '2026-01-15',
                '8000000',
                'user'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
