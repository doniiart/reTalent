<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Log Audit - Retalent HRIS</title>
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
            padding: 9px 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
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
        .status-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-error {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .module-badge {
            font-weight: bold;
            color: #18398a;
            background-color: #eef2fc;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9.5px;
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
                <div class="doc-title">LAPORAN AUDIT LOG & MONITORING AKTIVITAS</div>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <div class="meta-info">
                    <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB<br>
                    <strong>Periode:</strong> 
                    @if($startDate && $endDate)
                        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    @elseif($startDate)
                        Mulai {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                    @elseif($endDate)
                        Sampai {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    @else
                        Semua Riwayat Log
                    @endif
                    <br>
                    <strong>Total Log:</strong> {{ count($logs) }} Rekaman
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="4%">#</th>
                <th width="15%">Waktu & Tanggal</th>
                <th width="20%">Nama Staff / User</th>
                <th width="14%">Departemen</th>
                <th width="12%">Modul</th>
                <th width="24%">Detail Aktivitas</th>
                <th width="11%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                    <td>
                        <strong>{{ $log->user->name ?? 'Sistem' }}</strong><br>
                        <span style="color:#6b7280; font-size:8.5px;">{{ $log->user->employee->position->name ?? 'Staff' }}</span>
                    </td>
                    <td>{{ $log->user->employee->department->name ?? '-' }}</td>
                    <td>
                        <span class="module-badge">{{ $log->module ?? 'General' }}</span>
                    </td>
                    <td>{{ $log->activity }}</td>
                    <td>
                        @php
                            $statusStr = strtolower($log->status ?? 'success');
                        @endphp
                        @if($statusStr == 'success')
                            <span class="status-badge status-success">Success</span>
                        @elseif($statusStr == 'warning')
                            <span class="status-badge status-warning">Warning</span>
                        @else
                            <span class="status-badge status-error">{{ ucfirst($log->status ?? 'Error') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 25px; color: #9ca3af;">
                        Tidak ada rekaman log audit yang sesuai dengan filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Retalent HRIS System pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB.
    </div>
</body>
</html>
