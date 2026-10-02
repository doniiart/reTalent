{{-- Template PDF laporan rekapitulasi absensi karyawan --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Karyawan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f7fb; font-weight: bold; color: #555; }
        .text-center { text-align: center; }
        .status-present { color: #15a34a; font-weight: bold; }
        .status-late { color: #a16207; font-weight: bold; }
        .status-on_leave { color: #1d4ed8; font-weight: bold; }
        .status-absent { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rekap Absensi Karyawan</div>
        <div class="subtitle">Bulan: {{ $monthName }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Karyawan</th>
                <th style="width: 15%">Departemen</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 10%">Jam Masuk</th>
                <th style="width: 10%">Jam Keluar</th>
                <th style="width: 10%">Status</th>
                <th style="width: 10%">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $att)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $att->user->name ?? '-' }}<br><small style="color: #777">{{ $att->user->nip ?? '-' }}</small></td>
                    <td>{{ $att->user->employee->department->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('H:i') : '-' }}</td>
                    <td class="text-center">{{ $att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('H:i') : '-' }}</td>
                    <td class="text-center">
                        @if($att->status == 'present')
                            <span class="status-present">Hadir</span>
                        @elseif($att->status == 'late')
                            <span class="status-late">Terlambat</span>
                        @elseif($att->status == 'on_leave')
                            <span class="status-on_leave">Izin/Sakit</span>
                        @else
                            <span class="status-absent">Alpa</span>
                        @endif
                    </td>
                    <td>{{ $att->location ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data absensi untuk bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
