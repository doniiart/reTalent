<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Gaji - Retalent HRIS</title>
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
            padding: 8px 6px;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .table td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9.5px;
            vertical-align: middle;
        }
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .amount {
            text-align: right;
            font-family: monospace, sans-serif;
            font-size: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #eef2fc !important;
            border-top: 2px solid #18398a;
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
                <div class="doc-title">LAPORAN REKAPITULASI GAJI KARYAWAN</div>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <div class="meta-info">
                    <strong>Bulan / Tahun:</strong> {{ $month }} {{ $year }}<br>
                    <strong>Departemen:</strong> {{ $departmentName ?? 'Semua' }}<br>
                    <strong>Jabatan:</strong> {{ $positionName ?? 'Semua' }}<br>
                    <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="3%">#</th>
                <th width="10%">NIP</th>
                <th width="16%">Nama Karyawan</th>
                <th width="12%">Departemen</th>
                <th width="11%">Gaji Pokok</th>
                <th width="10%">Tunjangan</th>
                <th width="10%">Reward</th>
                <th width="10%">Potongan</th>
                <th width="12%">Gaji Bersih</th>
                <th width="6%">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGajiPokok = 0;
                $totalTunjangan = 0;
                $totalReward = 0;
                $totalPotongan = 0;
                $totalGajiBersih = 0;
            @endphp
            @forelse($payrolls as $index => $p)
                @php
                    $gajiPokok = $p->basic_salary ?? 0;
                    $tunjangan = $p->allowance ?? 0;
                    $reward = $p->reward ?? 0;
                    $potongan = ($p->deductions ?? 0) + ($p->jht_deduction ?? 0) + ($p->bpjs_deduction ?? 0);
                    $gajiBersih = $p->net_salary ?? ($gajiPokok + $tunjangan + $reward - $potongan);

                    $totalGajiPokok += $gajiPokok;
                    $totalTunjangan += $tunjangan;
                    $totalReward += $reward;
                    $totalPotongan += $potongan;
                    $totalGajiBersih += $gajiBersih;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $p->user->employee->nip ?? '-' }}</td>
                    <td><strong>{{ $p->user->name ?? '-' }}</strong></td>
                    <td>{{ $p->user->employee->department->name ?? '-' }}</td>
                    <td class="amount">Rp {{ number_format($gajiPokok, 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format($tunjangan, 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format($reward, 0, ',', '.') }}</td>
                    <td class="amount" style="color: #dc2626;">Rp {{ number_format($potongan, 0, ',', '.') }}</td>
                    <td class="amount" style="font-weight: bold; color: #18398a;">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @if(strtolower($p->status) == 'paid' || strtolower($p->status) == 'dibayar')
                            <span style="color: #16a34a; font-weight: bold;">Lunas</span>
                        @else
                            <span style="color: #d97706; font-weight: bold;">Draft</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 25px; color: #9ca3af;">
                        Tidak ada data gaji yang ditemukan untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($payrolls) > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; padding-right: 10px;">GRAND TOTAL:</td>
                    <td class="amount">Rp {{ number_format($totalGajiPokok, 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format($totalTunjangan, 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format($totalReward, 0, ',', '.') }}</td>
                    <td class="amount" style="color: #dc2626;">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                    <td class="amount" style="color: #18398a;">Rp {{ number_format($totalGajiBersih, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Retalent HRIS Payroll System pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} WIB.
    </div>
</body>
</html>
