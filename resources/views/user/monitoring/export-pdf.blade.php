<!DOCTYPE html>
<html>
<head>
    <title>Laporan Log Audit HRD</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #555; }
        .status-success { color: green; font-weight: bold; }
        .status-warning { color: orange; font-weight: bold; }
        .status-error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Log Audit Sistem</h2>
        <p>Rentang Waktu: 
            @if($startDate && $endDate)
                {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            @elseif($startDate)
                Sejak {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
            @elseif($endDate)
                Hingga {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            @else
                Semua Waktu
            @endif
        </p>
        <p>Diekspor pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Staf</th>
                <th>Departemen</th>
                <th>Jabatan (Role)</th>
                <th>Modul</th>
                <th>Aktivitas</th>
                <th>Waktu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                @php
                    $statusClass = $log->status == 'success' ? 'status-success' : ($log->status == 'warning' ? 'status-warning' : 'status-error');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->user->name ?? '-' }}</td>
                    <td>{{ $log->user->employee->department->name ?? '-' }}</td>
                    <td>{{ $log->user->employee->position->name ?? '-' }}</td>
                    <td>{{ $log->module }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="{{ $statusClass }}">{{ ucfirst($log->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data log.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
