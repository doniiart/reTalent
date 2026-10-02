<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $payroll->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #18398a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #18398a;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .employee-details {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .employee-details td {
            padding: 5px;
            vertical-align: top;
        }
        .employee-details .label {
            font-weight: bold;
            width: 120px;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th, .salary-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .salary-table th {
            background-color: #f8f9fa;
            text-align: left;
            font-weight: bold;
            color: #18398a;
        }
        .salary-table .amount {
            text-align: right;
        }
        .total-row td {
            font-weight: bold;
            font-size: 14px;
            background-color: #eef2fe;
            color: #18398a;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box {
            width: 300px;
            float: right;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Slip Gaji</h1>
            <p>Slip Gaji Karyawan - Periode {{ ucfirst($payroll->month) }} {{ $payroll->year }}</p>
        </div>

        <table class="employee-details">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: {{ $payroll->user->name }}</td>
                <td class="label">Status</td>
                <td>: {{ strtoupper($payroll->user->employee->employment_status ?? '-') }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td>: {{ $payroll->user->employee->nip ?? '-' }}</td>
                <td class="label">Departemen</td>
                <td>: {{ $payroll->user->employee->department->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td>: {{ $payroll->user->employee->position->name ?? '-' }}</td>
                <td class="label">Tipe Pekerjaan</td>
                <td>: {{ $payroll->user->employee->jobType->name ?? '-' }}</td>
            </tr>
        </table>

        <table class="salary-table">
            <thead>
                <tr>
                    <th colspan="2">Rincian Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gaji Pokok</td>
                    <td class="amount">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Tunjangan (Termasuk Transport & Makan)</td>
                    <td class="amount">Rp {{ number_format($payroll->total_allowance, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Reward / Insentif</td>
                    <td class="amount">Rp {{ number_format($payroll->total_reward, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Bonus Khusus (Premium)</td>
                    <td class="amount">Rp {{ number_format($payroll->premium, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="salary-table">
            <thead>
                <tr>
                    <th colspan="2">Rincian Potongan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Potongan Jaminan Hari Tua (JHT - 2%)</td>
                    <td class="amount" style="color: red;">- Rp {{ number_format($payroll->jht_deduction, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="salary-table" style="border: 2px solid #18398a;">
            <tr class="total-row">
                <td>TOTAL GAJI BERSIH (TAKE HOME PAY)</td>
                <td class="amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <p style="font-style: italic; font-size: 11px; color: #777;">* Slip gaji ini di-generate otomatis oleh sistem ReTalent HRIS dan sah tanpa tanda tangan basah.</p>

        <div class="footer">
            <div class="signature-box">
                <p>Sukoharjo, {{ date('d F Y') }}<br>Manager Human Growth</p>
                <div class="signature-line">
                    <strong>( Manager HG )</strong>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
