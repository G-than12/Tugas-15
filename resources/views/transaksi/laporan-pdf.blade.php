<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 2px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 16px;
        }

        .filter-info {
            margin-bottom: 12px;
            padding: 8px 10px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        .filter-info span {
            margin-right: 16px;
        }

        .summary {
            width: 100%;
            margin-bottom: 16px;
        }

        .summary td {
            width: 50%;
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        .summary .label {
            color: #777;
            display: block;
            font-size: 10px;
        }

        .summary .value {
            font-size: 14px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th,
        table.data td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        table.data th {
            background: #f0f0f0;
            font-size: 10px;
            text-transform: uppercase;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
        }

        .badge-dipinjam {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-dikembalikan {
            background: #d1fae5;
            color: #065f46;
        }

        .text-right {
            text-align: right;
        }

        .footer-note {
            margin-top: 16px;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>

<body>
    <h1>Laporan Transaksi Peminjaman</h1>
    <p class="subtitle">Dicetak pada {{ now()->format('d M Y H:i') }}</p>

    <div class="filter-info">
        <span><strong>Periode:</strong> {{ $filters['dari'] ?? '-' }} s/d {{ $filters['sampai'] ?? '-' }}</span>
        <span><strong>Status:</strong> {{ $filters['status'] }}</span>
        <span><strong>Anggota:</strong> {{ $filters['anggota'] }}</span>
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="label">Total Transaksi</span>
                <span class="value">{{ $totalTransaksi }}</span>
            </td>
            <td>
                <span class="label">Total Denda</span>
                <span class="value">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->kode_transaksi }}</td>
                    <td>{{ $transaksi->anggota->nama }}</td>
                    <td>{{ $transaksi->buku->judul }}</td>
                    <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                    <td>{{ $transaksi->tanggal_kembali->format('d M Y') }}</td>
                    <td>
                        @if ($transaksi->status == 'Dipinjam')
                            <span class="badge badge-dipinjam">Dipinjam</span>
                        @else
                            <span class="badge badge-dikembalikan">Dikembalikan</span>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $transaksi->denda > 0 ? 'Rp ' . number_format($transaksi->denda, 0, ',', '.') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:#999;">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer-note">Laporan ini digenerate otomatis dari Sistem Perpustakaan.</p>
</body>

</html>
