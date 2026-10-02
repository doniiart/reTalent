<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Announcement;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\Position;
use App\Models\JobType;

class SearchController extends Controller
{
    // Live Search Global & Context-Aware (Sesuai Menu Aktif)
    public function liveSearch(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        $context = $request->get('context', 'dashboard');

        if (strlen($keyword) < 1) {
            return response()->json(['results' => []]);
        }

        $user = auth()->user();
        $role = $user->roles->first()?->name ?? 'user';
        $results = [];

        // 1. Jika di Halaman Dashboard: Cari Semua Menu & Kategori
        if ($context === 'dashboard') {
            $menus = [
                ['title' => 'Dashboard Utama', 'keywords' => 'dashboard beranda home', 'url' => route("{$role}.dashboard"), 'category' => 'Menu', 'icon' => 'home'],
                ['title' => 'Absensi & Kehadiran', 'keywords' => 'absensi kehadiran clock in clock out hadir kerja jam masuk', 'url' => route("{$role}.attendance.index"), 'category' => 'Menu', 'icon' => 'calendar'],
                ['title' => 'Pengajuan Cuti & Izin', 'keywords' => 'cuti izin sakit libur permohonan jatah', 'url' => route("{$role}.leave.index"), 'category' => 'Menu', 'icon' => 'briefcase'],
                ['title' => 'Penggajian & Slip Gaji', 'keywords' => 'gaji payroll slip honor pendapatan tunjangan jht', 'url' => route("{$role}.payroll.index"), 'category' => 'Menu', 'icon' => 'cash'],
                ['title' => 'Pengumuman Perusahaan', 'keywords' => 'pengumuman berita informasi', 'url' => route("{$role}.announcements.index"), 'category' => 'Menu', 'icon' => 'speaker'],
                ['title' => 'Direktori Karyawan', 'keywords' => 'karyawan pegawai staff manajer hrd', 'url' => route("{$role}.employees.index"), 'category' => 'Menu', 'icon' => 'users'],
                ['title' => 'Pengaturan Akun & Profil', 'keywords' => 'pengaturan setting profil password kata sandi foto', 'url' => route("{$role}.settings.index"), 'category' => 'Menu', 'icon' => 'cog'],
            ];

            if (in_array($role, ['superadmin', 'admin'])) {
                $menus[] = ['title' => 'Log Aktivitas', 'keywords' => 'monitoring log audit aktivitas session riwayat', 'url' => route("{$role}.monitoring.index"), 'category' => 'Menu', 'icon' => 'shield'];
            }

            foreach ($menus as $m) {
                if (stripos($m['title'], $keyword) !== false || stripos($m['keywords'], $keyword) !== false) {
                    $results[] = [
                        'category' => 'Halaman Menu',
                        'title' => $m['title'],
                        'subtitle' => 'Navigasi Langsung',
                        'url' => $m['url'],
                        'type' => 'menu',
                        'badge' => 'Menu'
                    ];
                }
            }
        }

        // 2. Pencarian Karyawan (Context: employees ATAU dashboard)
        if (in_array($context, ['dashboard', 'employees'])) {
            $employees = User::has('employee')
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('nip', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%")
                      ->orWhereHas('employee.department', function ($dq) use ($keyword) {
                          $dq->where('name', 'like', "%{$keyword}%");
                      })
                      ->orWhereHas('employee.position', function ($pq) use ($keyword) {
                          $pq->where('name', 'like', "%{$keyword}%");
                      });
                })
                ->with(['employee.department', 'employee.position'])
                ->limit(8)
                ->get();

            foreach ($employees as $emp) {
                $position = $emp->employee->position->name ?? 'Karyawan';
                $dept = $emp->employee->department->name ?? '-';
                
                $url = match($role) {
                    'superadmin' => route('superadmin.employees.show', $emp->employee->id ?? 1),
                    'admin' => route('admin.employees.show', $emp->employee->id ?? 1),
                    default => route('user.employees.index'),
                };

                $results[] = [
                    'category' => 'Data Karyawan',
                    'title' => $emp->name,
                    'subtitle' => "{$position} • {$dept} (NIP: {$emp->nip})",
                    'url' => $url,
                    'type' => 'employee',
                    'badge' => 'Karyawan'
                ];
            }
        }

        // 3. Pencarian Payroll (Context: payroll ATAU dashboard)
        if (in_array($context, ['dashboard', 'payroll'])) {
            $payrollQuery = Payroll::with(['user.employee.department', 'user.employee.position']);
            if ($role === 'user') {
                $payrollQuery->where('user_id', $user->id);
            }
            $payrolls = $payrollQuery->where(function ($q) use ($keyword) {
                    $q->where('month', 'like', "%{$keyword}%")
                      ->orWhere('year', 'like', "%{$keyword}%")
                      ->orWhere('status', 'like', "%{$keyword}%")
                      ->orWhereHas('user', function ($uq) use ($keyword) {
                          $uq->where('name', 'like', "%{$keyword}%");
                      });
                })
                ->latest()
                ->limit(8)
                ->get();

            foreach ($payrolls as $p) {
                $recipient = $p->user->name ?? 'Karyawan';
                $monthYear = "{$p->month} {$p->year}";
                $netSalaryStr = 'Rp ' . number_format($p->net_salary ?? 0, 0, ',', '.');
                $url = match($role) {
                    'superadmin' => route('superadmin.payroll.index'),
                    'admin' => route('admin.payroll.index'),
                    default => route('user.payroll.index'),
                };

                $results[] = [
                    'category' => 'Data Gaji',
                    'title' => "Gaji {$monthYear} - {$recipient}",
                    'subtitle' => "Gaji Bersih: {$netSalaryStr} • Status: " . strtoupper($p->status),
                    'url' => $url,
                    'type' => 'payroll',
                    'badge' => 'Gaji'
                ];
            }
        }

        // 4. Pencarian Kehadiran (Context: attendance ATAU dashboard)
        if (in_array($context, ['dashboard', 'attendance'])) {
            $attQuery = Attendance::with(['user.employee.department']);
            if ($role === 'user') {
                $attQuery->where('user_id', $user->id);
            }
            $attendances = $attQuery->where(function ($q) use ($keyword) {
                    $q->where('date', 'like', "%{$keyword}%")
                      ->orWhere('status', 'like', "%{$keyword}%")
                      ->orWhere('location', 'like', "%{$keyword}%")
                      ->orWhereHas('user', function ($uq) use ($keyword) {
                          $uq->where('name', 'like', "%{$keyword}%");
                      });
                })
                ->latest('date')
                ->limit(8)
                ->get();

            foreach ($attendances as $att) {
                $userName = $att->user->name ?? 'Karyawan';
                $statusText = match(strtolower($att->status)) {
                    'present', 'hadir' => 'Hadir',
                    'late' => 'Terlambat',
                    'on_leave', 'izin' => 'Izin/Cuti',
                    default => ucfirst($att->status)
                };
                $timeInfo = "Masuk: " . ($att->time_in ?? '-') . " | Keluar: " . ($att->time_out ?? '-');
                $url = match($role) {
                    'superadmin' => route('superadmin.attendance.index'),
                    'admin' => route('admin.attendance.index'),
                    default => route('user.attendance.index'),
                };

                $results[] = [
                    'category' => 'Data Kehadiran',
                    'title' => "Absensi {$att->date} - {$userName}",
                    'subtitle' => "Status: {$statusText} • {$timeInfo}",
                    'url' => $url,
                    'type' => 'attendance',
                    'badge' => 'Absensi'
                ];
            }
        }

        // 5. Pencarian Cuti (Context: leave ATAU dashboard)
        if (in_array($context, ['dashboard', 'leave'])) {
            $leaveQuery = Leave::with('user');
            if ($role === 'user') {
                $leaveQuery->where('user_id', $user->id);
            }
            $leaves = $leaveQuery->where(function ($q) use ($keyword) {
                    $q->where('leave_type', 'like', "%{$keyword}%")
                      ->orWhere('reason', 'like', "%{$keyword}%")
                      ->orWhereHas('user', function ($uq) use ($keyword) {
                          $uq->where('name', 'like', "%{$keyword}%");
                      });
                })
                ->latest()
                ->limit(8)
                ->get();

            foreach ($leaves as $l) {
                $applicant = $l->user->name ?? 'Karyawan';
                $typeStr = ucfirst($l->leave_type ?? 'Cuti');
                $statusStr = strtoupper($l->status);
                $url = route("{$role}.leave.index");

                $results[] = [
                    'category' => 'Pengajuan Cuti',
                    'title' => "Cuti {$typeStr} - {$applicant}",
                    'subtitle' => "Status: {$statusStr} • \"{$l->reason}\"",
                    'url' => $url,
                    'type' => 'leave',
                    'badge' => 'Cuti'
                ];
            }
        }

        // 6. Pencarian Pengumuman (Context: announcements ATAU dashboard)
        if (in_array($context, ['dashboard', 'announcements'])) {
            $announcements = Announcement::where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('content', 'like', "%{$keyword}%");
                })
                ->latest()
                ->limit(6)
                ->get();

            foreach ($announcements as $ann) {
                $url = match($role) {
                    'superadmin' => route('superadmin.announcements.show', $ann->id),
                    'admin' => route('admin.announcements.show', $ann->id),
                    default => route('user.announcements.show', $ann->id),
                };

                $results[] = [
                    'category' => 'Pengumuman',
                    'title' => $ann->title,
                    'subtitle' => 'Diterbitkan ' . $ann->created_at->format('d M Y'),
                    'url' => $url,
                    'type' => 'announcement',
                    'badge' => 'Informasi'
                ];
            }
        }

        // 7. Pencarian Monitoring Log (Context: monitoring ATAU dashboard)
        if (in_array($context, ['dashboard', 'monitoring']) && in_array($role, ['superadmin', 'admin'])) {
            $logs = ActivityLog::with('user')
                ->where(function ($q) use ($keyword) {
                    $q->where('activity', 'like', "%{$keyword}%")
                      ->orWhere('module', 'like', "%{$keyword}%")
                      ->orWhere('ip_address', 'like', "%{$keyword}%")
                      ->orWhereHas('user', function ($uq) use ($keyword) {
                          $uq->where('name', 'like', "%{$keyword}%");
                      });
                })
                ->latest()
                ->limit(8)
                ->get();

            foreach ($logs as $log) {
                $userName = $log->user->name ?? 'System';
                $url = route("{$role}.monitoring.index");

                $results[] = [
                    'category' => 'Log Audit',
                    'title' => "{$log->activity} ({$userName})",
                    'subtitle' => "Modul: {$log->module} • IP: {$log->ip_address} • " . $log->created_at->diffForHumans(),
                    'url' => $url,
                    'type' => 'monitoring',
                    'badge' => 'Audit'
                ];
            }
        }


        // 9. Pencarian Pengaturan / Master Data (Context: settings ATAU dashboard)
        if (in_array($context, ['dashboard', 'settings'])) {
            $departments = Department::where('name', 'like', "%{$keyword}%")->get();
            foreach ($departments as $dept) {
                $results[] = [
                    'category' => 'Master Data',
                    'title' => "Departemen: {$dept->name}",
                    'subtitle' => 'Kelola di Pengaturan Master Data',
                    'url' => route("{$role}.settings.index"),
                    'type' => 'settings',
                    'badge' => 'Settings'
                ];
            }

            $positions = Position::where('name', 'like', "%{$keyword}%")->get();
            foreach ($positions as $pos) {
                $results[] = [
                    'category' => 'Master Data',
                    'title' => "Jabatan: {$pos->name}",
                    'subtitle' => 'Kelola di Pengaturan Master Data',
                    'url' => route("{$role}.settings.index"),
                    'type' => 'settings',
                    'badge' => 'Settings'
                ];
            }
        }

        return response()->json([
            'query' => $keyword,
            'context' => $context,
            'count' => count($results),
            'results' => $results
        ]);
    }
}
