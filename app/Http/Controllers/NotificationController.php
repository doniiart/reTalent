<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Tandai notifikasi sebagai sudah dibaca & alihkan ke URL tujuan yang sesuai role
    public function read($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if ($url) {
            // Penyesuaian dinamis jika rute notifikasi mengarah ke rute role lain
            if (str_contains($url, '/user/attendance') || str_contains($url, '/admin/attendance')) {
                if ($user->hasRole('superadmin')) {
                    $url = route('superadmin.attendance.index');
                } elseif ($user->hasRole('admin')) {
                    $url = route('admin.attendance.personal');
                } else {
                    $url = route('user.attendance.index');
                }
            } elseif (str_contains($url, '/user/leave') || str_contains($url, '/admin/leave')) {
                if ($user->hasRole('superadmin')) {
                    $url = route('superadmin.leave.index');
                } elseif ($user->hasRole('admin')) {
                    $url = route('admin.leave.index');
                } else {
                    $url = route('user.leave.index');
                }
            }

            return redirect($url);
        }

        return redirect()->back();
    }
    
    // Tandai semua notifikasi sebagai sudah dibaca
    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
