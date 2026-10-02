{{-- Template PDF laporan absensi harian (per tanggal) --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Harian</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #555; }
        .summary { margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }
        .summary-item { display: inline-block; margin-right: 20px; }
        .summary-label { font-weight: bold; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f7fb; font-weight: bold; color: #555; }
        .text-center { text-align: center; }
        .status-present { color: #15a34a; font-weight: bold; }
        .status-late { color: #a16207; font-weight: bold; }
        .status-on_leave { color: #1d4ed8; font-weight: bold; }
        .status-absent { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Absensi Harian</div>
        <div class="subtitle">Tanggal: {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}</div>
        @if($departmentName)
            <div class="subtitle">Departemen: {{ $departmentName }}</div>
        @endif
    </div>

    <div class="summary">
        <span class="summary-item"><span class="summary-label">Total Data:</span> {{ $attendances->count() }} karyawan</span>
        <span class="summary-item"><span class="summary-label">Hadir:</span> {{ $attendances->whereIn('status', ['present', 'late'])->count() }}</span>
        <span class="summary-item"><span class="summary-label">Terlambat:</span> {{ $attendances->where('status', 'late')->count() }}</span>
        <span class="summary-item"><span class="summary-label">Izin/Sakit:</span> {{ $attendances->where('status', 'on_leave')->count() }}</span>
        <span class="summary-item"><span class="summary-label">Alpa:</span> {{ $attendances->where('status', 'absent')->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 10%">NIP</th>
                <th style="width: 22%">Nama Karyawan</th>
                <th style="width: 15%">Departemen</th>
                <th style="width: 10%">Jam Masuk</th>
                <th style="width: 10%">Jam Keluar</th>
                <th style="width: 13%">Status</th>
                <th style="width: 15%">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $att)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $att->user->nip ?? '-' }}</td>
                    <td>{{ $att->user->name ?? '-' }}</td>
                    <td>{{ $att->user->employee->department->name ?? '-' }}</td>
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
                    <td colspan="8" class="text-center">Tidak ada data absensi untuk tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
        <p style="font-style: italic;">* Dokumen ini di-generate otomatis oleh sistem ReTalent HRIS</p>
    </div>
</body>
</html>
