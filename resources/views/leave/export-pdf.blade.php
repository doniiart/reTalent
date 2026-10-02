{{-- Template PDF laporan rekapitulasi pengajuan cuti --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengajuan Cuti - Retalent HRIS</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2.5px solid #18398a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #18398a;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: bold;
            color: #4b5563;
            margin-top: 4px;
        }
        .meta-info {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.5;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background-color: #18398a;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 8px;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .table td {
            padding: 7px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9.5px;
            vertical-align: top;
        }
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
            border-top: 1px solid #f3f4f6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <div class="company-name">RETALENT HRIS</div>
                <div class="doc-title">LAPORAN REKAPITULASI PENGAJUAN CUTI & IZIN</div>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <div class="meta-info">
                    <strong>Bulan / Tahun:</strong> {{ $month ?? 'Semua' }} {{ $year ?? '' }}<br>
                    <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB<br>
                    <strong>Total Pengajuan:</strong> {{ count($leaves) }}
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="22%">Nama Karyawan</th>
                <th width="14%">Tipe Cuti</th>
                <th width="18%">Rentang Tanggal</th>
                <th width="8%">Durasi</th>
                <th width="22%">Alasan</th>
                <th width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $index => $leave)
                @php
                    $start = \Carbon\Carbon::parse($leave->start_date);
                    $end = \Carbon\Carbon::parse($leave->end_date);
                    $days = $start->diffInDays($end) + 1;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $leave->user->name ?? '-' }}</strong><br>
                        <span style="color:#6b7280; font-size:8.5px;">{{ $leave->user->employee->department->name ?? '-' }}</span>
                    </td>
                    <td>{{ ucfirst($leave->type ?? 'Cuti Tahunan') }}</td>
                    <td>{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</td>
                    <td style="text-align: center;">{{ $days }} Hari</td>
                    <td>{{ $leave->reason ?? '-' }}</td>
                    <td>
                        @php
                            $st = strtolower($leave->status ?? 'pending');
                        @endphp
                        @if($st == 'approved')
                            <span class="status-badge status-approved">Disetujui</span>
                        @elseif($st == 'rejected')
                            <span class="status-badge status-rejected">Ditolak</span>
                        @else
                            <span class="status-badge status-pending">Pending</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 25px; color: #9ca3af;">
                        Tidak ada data pengajuan cuti yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Retalent HRIS Leave System pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB.
    </div>
</body>
</html>
