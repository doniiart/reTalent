<!DOCTYPE html>
<html>
<head>
    <title>Laporan Cuti Karyawan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; color: #555; }
        .status-approved { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Cuti Karyawan</h2>
        <p>Periode: {{ $month ? \Carbon\Carbon::create()->month($month)->translatedFormat('F') : 'Semua Bulan' }} {{ $year ?: 'Semua Tahun' }}</p>
        <p>Diekspor pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Departemen</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Durasi (Hari)</th>
                <th>Alasan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $index => $leave)
                @php
                    $duration = \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
                    $statusClass = $leave->status == 'approved' ? 'status-approved' : ($leave->status == 'pending' ? 'status-pending' : 'status-rejected');
                    $statusLabel = [
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak'
                    ];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $leave->user->name ?? '-' }}</td>
                    <td>{{ $leave->user->employee->department->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                    <td>{{ $duration }}</td>
                    <td>{{ $leave->reason }}</td>
                    <td class="{{ $statusClass }}">{{ $statusLabel[$leave->status] ?? $leave->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data cuti.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
