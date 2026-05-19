<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #222; }
        h2 { text-align: center; font-size: 14px; margin-bottom: 4px; }
        p.sub { text-align: center; font-size: 10px; color: #555; margin: 0 0 12px; }
        .ringkasan { display: flex; gap: 20px; margin-bottom: 14px; }
        .ring-box { border: 1px solid #ccc; border-radius: 6px; padding: 8px 14px; }
        .ring-label { font-size: 9px; color: #666; }
        .ring-value { font-size: 13px; font-weight: bold; }
        .hari-header { background: #e8f0fe; padding: 6px 10px; border-radius: 4px 4px 0 0;
                       font-weight: bold; font-size: 11px; margin-top: 14px;
                       display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f3f4f6; padding: 5px 8px; text-align: left; border: 1px solid #ddd; }
        td { padding: 5px 8px; border: 1px solid #ddd; }
        tfoot td { background: #f0fdf4; font-weight: bold; color: #166534; }
        .text-right { text-align: right; }
        .grand-total { margin-top: 16px; text-align: right; font-size: 12px;
                       font-weight: bold; border-top: 2px solid #222; padding-top: 6px; }
    </style>
</head>
<body>
    <h2>LAPORAN HARIAN PEMBAYARAN</h2>
    <p class="sub">SMK YPT TEGAL &mdash; Dicetak: {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>

    <div class="ringkasan">
        <div class="ring-box">
            <div class="ring-label">Total Transaksi</div>
            <div class="ring-value">{{ $totalTransaksi }}</div>
        </div>
        <div class="ring-box">
            <div class="ring-label">Grand Total</div>
            <div class="ring-value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
    </div>

    @forelse($perHari as $hari)
        <div class="hari-header">
            <span>{{ $hari['label'] }} ({{ $hari['jumlah'] }} transaksi)</span>
            <span>Rp {{ number_format($hari['total'], 0, ',', '.') }}</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="22%">Nama Siswa</th>
                    <th width="12%">Kelas</th>
                    <th width="40%">Item Pembayaran</th>
                    <th width="22%" class="text-right">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hari['pembayaran'] as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->siswa->nama ?? '-' }}</td>
                    <td>{{ $p->siswa->kelas ?? '-' }}</td>
                    <td>
                        @foreach($p->pembayaranDetail as $detail)
                            {{ $detail->spp->nama ?? '-' }}<br>
                        @endforeach
                        @foreach($p->angsuranDu as $du)
                            {{ $du->spp->nama ?? 'DU' }} - Angsuran ke-{{ $du->angsuran_ke }}<br>
                        @endforeach
                    </td>
                    <td class="text-right">Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">Total {{ $hari['label'] }}:</td>
                    <td class="text-right">Rp {{ number_format($hari['total'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    @empty
        <p style="text-align:center; color:#999; margin-top:20px;">Tidak ada data pembayaran</p>
    @endforelse

    <div class="grand-total">
        GRAND TOTAL: Rp {{ number_format($grandTotal, 0, ',', '.') }}
    </div>
</body>
</html>
