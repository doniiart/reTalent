<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log if user is authenticated and the method modifies data
        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $path = $request->path();
            
            // Determine module and activity based on path
            $module = 'Sistem';
            $activity = 'Mengubah data';

            if (str_contains($path, 'employee')) {
                $module = 'Karyawan';
                $activity = $request->method() == 'POST' ? 'Menambah data karyawan' : 'Memperbarui data karyawan';
                if ($request->method() == 'DELETE') $activity = 'Menghapus data karyawan';
            } elseif (str_contains($path, 'attendance')) {
                $module = 'Kehadiran';
                $activity = 'Melakukan presensi / absen';
            } elseif (str_contains($path, 'leave')) {
                $module = 'Cuti';
                $activity = 'Mengajukan/mengubah status cuti';
            } elseif (str_contains($path, 'payroll')) {
                $module = 'Gaji';
                $activity = 'Memperbarui data gaji';
            } elseif (str_contains($path, 'settings')) {
                $module = 'Pengaturan';
                $activity = 'Mengubah pengaturan sistem';
            }

            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'activity' => $activity . ' (' . $request->method() . ' ' . $path . ')',
                'module' => $module,
                'status' => $response->isSuccessful() || $response->isRedirection() ? 'success' : 'error',
                'ip_address' => $request->ip()
            ]);
        }

        return $response;
    }
}
