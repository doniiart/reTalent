<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

$notifications = DB::table('notifications')->get();
$updated = 0;

foreach ($notifications as $n) {
    $data = json_decode($n->data, true);
    if (!$data || !isset($data['url'])) continue;

    $user = User::find($n->notifiable_id);
    if (!$user) continue;

    $url = $data['url'];
    $role = $user->roles->first()?->name ?? 'user';

    // Correct URL paths based on user role
    if (str_contains($url, '/user/attendance')) {
        if ($role === 'superadmin') {
            $data['url'] = route('superadmin.attendance.index');
        } elseif ($role === 'admin') {
            $data['url'] = route('admin.attendance.personal');
        }
    } elseif (str_contains($url, '/user/leave')) {
        if ($role === 'superadmin') {
            $data['url'] = route('superadmin.leave.index');
        } elseif ($role === 'admin') {
            $data['url'] = route('admin.leave.index');
        }
    }

    DB::table('notifications')->where('id', $n->id)->update([
        'data' => json_encode($data)
    ]);
    $updated++;
}

echo "SUCCESS: Updated $updated notification records in database.\n";
