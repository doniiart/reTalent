<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     * Mengembalikan file tampilan (view) sesuai dengan role pengguna yang login.
     */
    public function render(): View
    {
        $user = auth()->user();
        if ($user) {
            // Memeriksa role pengguna saat ini dan memuat folder layout yang sesuai
            // agar layout (termasuk sidebar) menyesuaikan dengan akses mereka.
            if ($user->hasRole('superadmin')) {
                return view('superadmin.layouts.app');
            } elseif ($user->hasRole('admin')) {
                return view('admin.layouts.app');
            } else {
                return view('user.layouts.app');
            }
        }
        
        // Default layout jika user belum login atau sesi telah berakhir
        return view('layouts.app');
    }
}
