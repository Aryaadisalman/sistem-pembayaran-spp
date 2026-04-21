<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran SPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-title {
            text-align: center;
            margin: 15px 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .receipt-info-left {
            flex: 1;
            text-align: left;
        }
        .receipt-info-right {
            flex: 1;
            text-align: right;
        }
        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .receipt-table th, .receipt-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .receipt-table th {
            background-color: #f9f9f9;
            font-size: 14px;
        }
        .receipt-table td {
            font-size: 14px;
        }
        .receipt-total {
            text-align: right;
            font-weight: bold;
            margin-top: 15px;
            font-size: 14px;
        }
        .signature {
            margin-top: 30px;
            text-align: right;
            font-size: 14px;
        }
        .stamp-image {
            position: absolute;
            right: 100px;
            margin-top: -40px;
            width: 120px;
            height: auto;
            transform: rotate(-15deg);
            opacity: 0.9;
            z-index: 10;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 15%; text-align: left; border: none;">
                    <img src="{{ public_path('logo/icon.png') }}" style="width: 90px; height: auto;">
                </td>
                <td style="width: 85%; text-align: center; border: none;">
                    <h1 style="font-size: 18px; margin: 0; font-weight: bold;">SMK YPT KOTA TEGAL</h1>
                    <p style="font-size: 14px; margin: 5px 0;">Jl. Dr. Setiabudi No. 163, RT 9 / RW 4, Kelurahan Panggung, Kecamatan Tegal Timur, Kota Tegal, Jawa Tengah</p>
                </td>
            </tr>
        </table>
        <hr style="border-top: 3px solid #000; margin-top: 10px; margin-bottom: 2px;">
        <hr style="border-top: 1px solid #000; margin-top: 2px; margin-bottom: 20px;">
    </div>
        
        <h2 class="receipt-title">KWITANSI PEMBAYARAN</h2>
        
        <div class="receipt-info">
            <div class="receipt-info-left">
                <p><strong>Telah diterima dari:</strong> {{ $siswa->nama ?? 'Siswa' }} / {{ $siswa->nisn ?? '-' }}</p>
            </div>
            <div class="receipt-info-right">
                <p><strong>No. Kwitansi:</strong> {{ $nomor_kwitansi }}</p>
                <p><strong>Tanggal Bayar:</strong> {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>
        
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Keterangan Pembayaran</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran->pembayaranDetail as $detail)
                <tr>
                    <td>{{ $detail->spp->nama ?? 'Item Pembayaran' }}</td>
                    <td>Rp. {{ number_format($detail->biaya, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="receipt-total">
            <p>Total Pembayaran: Rp. {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</p>
        </div>
        
        <div class="signature">
            <p>Tegal, {{ $tanggal }}</p>
            <p>Penanggung jawab,</p>
            <br><br>
            <img src="{{ public_path('logo/stampel.png') }}" alt="Stempel Pembayaran" class="stamp-image">
            <p><strong>Bendahara</strong></p>
        </div>
        
        <div class="footer">
            Kwitansi ini dicetak oleh sistem dan merupakan bukti pembayaran yang sah.
        </div>
    </div>
</body>
</html>
