{{-- Template PDF laporan data seluruh karyawan --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Karyawan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #18398a; padding-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; color: #18398a; margin-bottom: 5px; }
        .subtitle { font-size: 12px; color: #555; }
        .summary { margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }
        .summary-item { display: inline-block; margin-right: 25px; }
        .summary-label { font-weight: bold; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 10px; }
        th { background-color: #f4f7fb; font-weight: bold; color: #555; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-tetap { background-color: #dcfce7; color: #15803d; }
        .badge-kontrak { background-color: #fef3c7; color: #92400e; }
        .badge-intership { background-color: #dbeafe; color: #1e40af; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Data Seluruh Karyawan</div>
        <div class="subtitle">ReTalent HRIS</div>
        <div class="subtitle" style="margin-top: 5px;">
            Filter:
            Departemen: {{ $departmentName ?? 'Semua' }} |
            Status: {{ $statusName ?? 'Semua' }}
        </div>
    </div>

    <div class="summary">
        <span class="summary-item"><span class="summary-label">Total Karyawan:</span> {{ $employees->count() }}</span>
        <span class="summary-item"><span class="summary-label">Tetap:</span> {{ $employees->where('employment_status', 'tetap')->count() }}</span>
        <span class="summary-item"><span class="summary-label">Kontrak:</span> {{ $employees->where('employment_status', 'kontrak')->count() }}</span>
        <span class="summary-item"><span class="summary-label">Magang:</span> {{ $employees->where('employment_status', 'intership')->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 10%">NIP</th>
                <th style="width: 16%">Nama Lengkap</th>
                <th style="width: 14%">Email</th>
                <th style="width: 12%">Departemen</th>
                <th style="width: 12%">Jabatan</th>
                <th style="width: 9%">Status</th>
                <th style="width: 10%">Tgl Bergabung</th>
                <th style="width: 13%">No. HP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $emp->user->nip ?? '-' }}</td>
                    <td>{{ $emp->user->name ?? '-' }}</td>
                    <td>{{ $emp->user->email ?? '-' }}</td>
                    <td>{{ $emp->department->name ?? '-' }}</td>
                    <td>{{ $emp->position->name ?? '-' }}</td>
                    <td class="text-center">
                        @if($emp->employment_status == 'tetap')
                            <span class="badge badge-tetap">Tetap</span>
                        @elseif($emp->employment_status == 'kontrak')
                            <span class="badge badge-kontrak">Kontrak</span>
                        @else
                            <span class="badge badge-intership">Magang</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $emp->join_date ? \Carbon\Carbon::parse($emp->join_date)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $emp->phone ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data karyawan.</td>
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
