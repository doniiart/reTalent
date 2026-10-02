<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penggajian Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; font-size: 16px; }
        .header p { margin: 5px 0 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f4f7fb; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { margin-top: 20px; }
        .summary table { width: 50%; float: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penggajian Karyawan</h2>
        <p>Periode: {{ $month }} {{ $year }}</p>
        <p>
            Filter: 
            Departemen: {{ $departmentName ?? 'Semua' }} | 
            Jabatan: {{ $positionName ?? 'Semua' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th class="text-right">Gaji Pokok</th>
                <th class="text-right">Premi</th>
                <th class="text-right">Tunjangan</th>
                <th class="text-right">Reward</th>
                <th class="text-right">Potongan JHT</th>
                <th class="text-right">Total Gaji</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGaji = 0; @endphp
            @forelse($payrolls as $index => $payroll)
                @php $totalGaji += $payroll->net_salary; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payroll->user->name }}</td>
                    <td>{{ $payroll->user->employee->position->name ?? '-' }}</td>
                    <td class="text-right">{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($payroll->premium, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($payroll->total_reward, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($payroll->jht_deduction, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">{{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $payroll->status == 'paid' ? 'Selesai' : 'Draft' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data penggajian untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8" class="text-right">Grand Total:</th>
                <th class="text-right">{{ number_format($totalGaji, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
    </div>
</body>
</html>
