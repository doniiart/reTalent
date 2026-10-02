{{-- Template PDF profil karyawan individu --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profil Karyawan - {{ $employee->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .container { width: 100%; margin: 0 auto; }
        .header {
            text-align: center;
            border-bottom: 2px solid #18398a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #18398a;
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }
        .header p { margin: 5px 0 0; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #18398a;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .info-table td { padding: 5px 10px; vertical-align: top; }
        .info-table .label { font-weight: bold; color: #555; width: 150px; }
        .info-table .value { color: #333; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        .data-table th { background-color: #f4f7fb; font-weight: bold; color: #555; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .status-badge { padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .badge-present { background-color: #dcfce7; color: #15803d; }
        .badge-late { background-color: #fef3c7; color: #92400e; }
        .badge-on_leave { background-color: #dbeafe; color: #1e40af; }
        .badge-absent { background-color: #fee2e2; color: #dc2626; }
        .footer { margin-top: 40px; }
        .signature-box { width: 250px; float: right; text-align: center; }
        .signature-line { margin-top: 60px; border-top: 1px solid #333; padding-top: 5px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Profil Karyawan</h1>
            <p>Data Lengkap Karyawan - ReTalent HRIS</p>
        </div>

        <!-- Informasi Pribadi -->
        <div class="section">
            <div class="section-title">Informasi Pribadi</div>
            <table class="info-table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="value">: {{ $employee->user->name }}</td>
                    <td class="label">NIP</td>
                    <td class="value">: {{ $employee->user->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">: {{ $employee->user->email }}</td>
                    <td class="label">No. HP</td>
                    <td class="value">: {{ $employee->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="value" colspan="3">: {{ $employee->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Pendidikan Terakhir</td>
                    <td class="value" colspan="3">: {{ $employee->last_education ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Informasi Pekerjaan -->
        <div class="section">
            <div class="section-title">Informasi Pekerjaan</div>
            <table class="info-table">
                <tr>
                    <td class="label">Departemen</td>
                    <td class="value">: {{ $employee->department->name ?? '-' }}</td>
                    <td class="label">Jabatan</td>
                    <td class="value">: {{ $employee->position->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Pekerjaan</td>
                    <td class="value">: {{ $employee->jobType->name ?? '-' }}</td>
                    <td class="label">Status</td>
                    <td class="value">: {{ strtoupper($employee->employment_status ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Lokasi Kerja</td>
                    <td class="value">: {{ $employee->work_location ?? '-' }}</td>
                    <td class="label">Tanggal Bergabung</td>
                    <td class="value">: {{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d F Y') : '-' }}</td>
                </tr>
            </table>
        </div>





        <p style="font-style: italic; font-size: 10px; color: #777; margin-top: 20px;">
            * Dokumen ini di-generate otomatis oleh sistem ReTalent HRIS dan sah tanpa tanda tangan basah.
        </p>

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
