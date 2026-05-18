<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Siswa Lunas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
        }
        .status-lunas {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 15%; text-align: left; border: none;">
                    <img src="{{ str_replace(DIRECTORY_SEPARATOR, '/', public_path('logo/logo.png')) }}" style="width: 90px; height: auto;">
                </td>
                <td style="width: 85%; text-align: center; border: none;">
                    <h1 style="font-size: 18px; margin: 0; font-weight: bold;">SMK YPT KOTA TEGAL</h1>
                    <p style="font-size: 14px; margin: 5px 0;">Jl. Dr. Setiabudi No. 163, Kelurahan Panggung, Kecamatan Tegal Timur, Kota Tegal, Provinsi Jawa Tengah</p>
                </td>
            </tr>
        </table>
        <hr style="border-top: 3px solid #000; margin-top: 10px; margin-bottom: 2px;">
        <hr style="border-top: 1px solid #000; margin-top: 2px; margin-bottom: 20px;">

        <h1 style="font-size: 16px;">LAPORAN SISWA LUNAS SPP</h1>
        <p>Kelas: {{ $kelas }}</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($siswa) > 0)
                @php $no = 1; @endphp
                @foreach($siswaByKelas as $namaKelas => $siswaDiKelas)
                <tr>
                    <td colspan="6" style="background-color: #d1e7dd; font-weight: bold; padding: 6px 8px;">
                        Kelas {{ $namaKelas }} ({{ count($siswaDiKelas) }} siswa)
                    </td>
                </tr>
                @foreach($siswaDiKelas as $s)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kelas }}</td>
                    <td>{{ $s->user->email }}</td>
                    <td class="status-lunas">Lunas</td>
                </tr>
                @endforeach
                @endforeach
            @else
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada siswa yang lunas</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Total Siswa Lunas: {{ count($siswa) }}</p>
        <p>
            <br><br>
            Mengetahui<br><br><br><br>
            <strong>Muhammad Sultoni, S.Pd., M.Si.</strong><br>
            Kepala Sekolah
        </p>
    </div>
</body>
</html>