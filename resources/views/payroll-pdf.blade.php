<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #1e3a8a; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 5px; }
        .rincian-table { width: 100%; border-collapse: collapse; }
        .rincian-table th, .rincian-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .rincian-table th { background-color: #f8f9fa; }
        .total-row font { font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>ReTalent HRIS</h2>
        <p>Slip Gaji Karyawan</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>Nama</strong></td>
            <td width="30%">: {{ $user->name }}</td>
            <td width="20%"><strong>Periode</strong></td>
            <td width="30%">: Oktober 2023</td>
        </tr>
        <tr>
            <td><strong>Jabatan</strong></td>
            <td>: {{ $user->jabatan ?? 'Karyawan' }}</td>
            <td><strong>Email</strong></td>
            <td>: {{ $user->email }}</td>
        </tr>
    </table>

    <table class="rincian-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-right">{{ number_format($gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td class="text-right">{{ number_format($tunjangan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Potongan</td>
                <td class="text-right text-red">- {{ number_format($potongan, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td>TOTAL BERSIH</td>
                <td class="text-right">{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Surakarta, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>HR Department</strong></p>
    </div>

</body>
</html>