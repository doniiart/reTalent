<?php

namespace App\Http\Controllers\User;

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
    // Menampilkan direktori / daftar karyawan untuk User
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Employee::with(['user', 'department', 'position', 'jobType']);
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }
        
        $totalEmployees = Employee::count();

        if (!auth()->user()->hasRole(['superadmin', 'admin'])) {
            $employees = $query->paginate(12)->withQueryString();
            $departments = Department::all();
            
            $totalDepartments = Department::count();
            $topDepartments = Department::withCount('employees')
                                ->orderByDesc('employees_count')
                                ->take(3)
                                ->get();
                                
            return view('user.employees.directory', compact(
                'employees', 'departments', 'totalEmployees', 'totalDepartments', 'topDepartments'
            ));
        }

        $perPage = (int) $request->input('per_page', 10);
        $employees = $query->paginate($perPage)->withQueryString();
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();

        $activeEmployees = Employee::whereIn('employment_status', ['tetap', 'kontrak'])->count();
        $probationEmployees = Employee::where('employment_status', 'intership')->count();
        
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

        return view('user.employees.index', compact(
            'employees', 'departments', 'positions', 'jobTypes',
            'totalEmployees', 'activeEmployees', 'probationEmployees', 'attendancePercentage',
            'newEmployeesThisMonth', 'todayAttendance', 'recentAttendees'
        ));
    }

    // Form tambah karyawan
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        return view('user.employees.create', compact('departments', 'positions', 'jobTypes'));
    }

    // Simpan data karyawan baru
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
            $user = User::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'password' => Hash::make('12345'),
            ]);

            $user->assignRole($request->role);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }
            
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
            
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'job_type_id' => $request->job_type_id,
                'photo' => $photoPath,
                'phone' => $request->phone,
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

        return redirect()->route('user.employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    // Detail karyawan
    public function show(string $id)
    {
        $employee = Employee::with(['user', 'department', 'position', 'jobType'])->findOrFail($id);
        return view('user.employees.show', compact('employee'));
    }

    // Form edit karyawan
    public function edit(string $id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $departments = Department::all();
        $positions = Position::all();
        $jobTypes = JobType::all();
        return view('user.employees.edit', compact('employee', 'departments', 'positions', 'jobTypes'));
    }

    // Update data karyawan
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
            $user = $employee->user;
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->has('role')) {
                $user->syncRoles([$request->role]);
            }

            $photoPath = $employee->photo;
            if ($request->hasFile('photo')) {
                if ($photoPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('photos', 'public');
            }
            
            $employeeData = [
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'job_type_id' => $request->job_type_id,
                'photo' => $photoPath,
                'phone' => $request->phone,
                'work_location' => $request->work_location,
                'employment_status' => $request->employment_status,
                'join_date' => $request->join_date,
            ];

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

        return redirect()->route('user.employees.index')->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    // Hapus karyawan & user
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;
        
        if ($employee->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($employee->photo);
        }
        
        $employee->delete();
        $user->delete();

        return redirect()->route('user.employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
