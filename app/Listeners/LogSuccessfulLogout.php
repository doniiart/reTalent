<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event->user) {
            \App\Models\ActivityLog::create([
                'user_id' => $event->user->id,
                'activity' => 'User Logout',
                'module' => 'Autentikasi',
                'status' => 'success',
                'ip_address' => request()->ip()
            ]);
        }
    }
}
