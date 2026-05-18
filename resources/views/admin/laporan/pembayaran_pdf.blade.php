<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemasukan Pembayaran</title>
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
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-ditolak {
            color: red;
            font-weight: bold;
        }
        .status-belum {
            color: gray;
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
                    <h1 style="font-size: 18px; margin: 0; font-weight: bold;">SMK YPT KOTA KOTA TEGAL</h1>
                    <p style="font-size: 14px; margin: 5px 0;">Jl. Dr. Setiabudi No. 163, Kelurahan Panggung, Kecamatan Tegal Timur, Kota Tegal, Provinsi Jawa Tengah</p>
                </td>
            </tr>
        </table>
        <hr style="border-top: 3px solid #000; margin-top: 10px; margin-bottom: 2px;">
        <hr style="border-top: 1px solid #000; margin-top: 2px; margin-bottom: 20px;">

        <h1 style="font-size: 16px;">LAPORAN PEMBAYARAN SISWA</h1>
        <p>Periode: {{ request('dari_tanggal') ? \Carbon\Carbon::parse(request('dari_tanggal'))->format('d/m/Y') : 'Semua' }} 
            - {{ request('sampai_tanggal') ? \Carbon\Carbon::parse(request('sampai_tanggal'))->format('d/m/Y') : 'Semua' }}</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Item Pembayaran</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
    @php
        $pembayaranByKelas = $pembayaran->groupBy(fn($p) => $p->siswa->kelas ?? '');
        $no = 1;
    @endphp

    @if($pembayaran->count() > 0)

        @foreach($pembayaranByKelas as $namaKelas => $itemKelas)
            <tr>
                <td colspan="7" style="background-color: #cce5ff; font-weight: bold; padding: 6px 8px;">
                    Kelas {{ $namaKelas }} ({{ count($itemKelas) }} transaksi)
                </td>
            </tr>

            @foreach($itemKelas->sortBy(fn($p) => $p->siswa->nama) as $p)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
                    <td>{{ $p->siswa->nama }}</td>
                    <td>{{ $p->siswa->kelas }}</td>
                    <td>
                        @foreach($p->pembayaranDetail as $detail)
                            {{ $detail->spp->nama }}<br>
                        @endforeach
                        @foreach($p->angsuranDu as $du)
                            {{ ($du->spp->nama ?? 'DU') }} - Angsuran ke-{{ $du->angsuran_ke }}<br>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                    <td class="status-lunas">Sudah Bayar</td>
                </tr>
            @endforeach

        @endforeach

    @else
        <tr>
            <td colspan="7" style="text-align: center;">Tidak ada data pembayaran</td>
        </tr>
    @endif

</tbody>
    </table>

    <div class="footer">
        <p>Total Pembayaran: {{ $pembayaran->count() }}</p>
        <p>Total Nominal: Rp {{ number_format($pembayaran->sum('total_bayar'), 0, ',', '.') }}</p>
        <p>
            <br><br>
            Mengetahui<br><br><br><br>
            <strong>Muhammad Sultoni, S.Pd., M.Si.</strong><br>
            Kepala Sekolah
        </p>
    </div>
</body>
</html>