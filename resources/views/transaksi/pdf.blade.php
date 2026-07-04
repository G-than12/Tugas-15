<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        /* Pengaturan Dasar & Font */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #2d3748;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat (Header) */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-bottom: 3px double #1e3a8a;
            padding-bottom: 10px;
        }

        .kop-surat td {
            border: none;
            padding: 0;
        }

        .kop-title {
            text-align: center;
        }

        .kop-title h1 {
            font-size: 20px;
            color: #1e3a8a;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-title p {
            font-size: 10px;
            color: #4a5568;
            margin: 2px 0;
        }

        /* Metadata & Info Laporan */
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .meta-info td {
            padding: 2px 0;
            border: none;
        }

        /* Ringkasan Statistik (Summary Cards) */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-table td {
            width: 33.33%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            text-align: center;
            border-radius: 4px;
        }

        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
        }

        .summary-value.denda {
            color: #ef4444;
        }

        /* Tabel Data Utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }

        .data-table td {
            padding: 8px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        /* Zebra Striping */
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Alignment Utilities */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 9999px;
            text-align: center;
            text-transform: uppercase;
        }

        .badge-dipinjam {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-dikembalikan {
            background-color: #d1fae5;
            color: #059669;
        }

        .badge-terlambat {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Bagian Tanda Tangan */
        .ttd-container {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .ttd-container td {
            border: none;
            width: 50%;
        }

        .ttd-box {
            text-align: center;
            font-size: 11px;
        }

        .ttd-space {
            height: 60px;
        }

        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Footer Halaman */
        .footer-note {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <!-- Kop Surat Formal -->
    <table class="kop-surat">
        <tr>
            <td class="kop-title">
                <h1>Sistem Informasi Perpustakaan</h1>
                <p>Jl. Jenderal Sudirman No. 123, Batang, Jawa Tengah, Indonesia</p>
                <p>Telepon: (0285) 123456 | Email: perpustakaan@batang.go.id</p>
            </td>
        </tr>
    </table>

    <!-- Info Dokumen -->
    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Jenis Dokumen</strong></td>
            <td width="35%">: Laporan Transaksi Peminjaman Buku</td>
            <td width="15%"><strong>Tanggal Cetak</strong></td>
            <td width="35%">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Status Filter</strong></td>
            <td>: {{ request('status') ? request('status') : 'Semua Transaksi' }}</td>
            <td><strong>Periode</strong></td>
            <td>:
                @if (request('tanggal_pinjam_start') && request('tanggal_pinjam_end'))
                    {{ \Carbon\Carbon::parse(request('tanggal_pinjam_start'))->format('d/m/Y') }} s.d
                    {{ \Carbon\Carbon::parse(request('tanggal_pinjam_end'))->format('d/m/Y') }}
                @else
                    Semua Waktu
                @endif
            </td>
        </tr>
    </table>

    <!-- Statistik Ringkasan -->
    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">Total Transaksi</span>
                <span class="summary-value">{{ $transaksis->count() }}</span>
            </td>
            <td>
                <span class="summary-label">Sedang Dipinjam</span>
                <span class="summary-value">{{ $transaksis->where('status', 'Dipinjam')->count() }}</span>
            </td>
            <td>
                <span class="summary-label">Total Akumulasi Denda</span>
                <span class="summary-value denda">
                    Rp
                    {{ number_format($transaksis->sum(function ($t) {return $t->status === 'Dipinjam' ? $t->estimasi_denda : $t->denda;}),0,',','.') }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Tabel Data Utama dengan Kolom Denda Spesifik -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="12%" class="text-center">Kode TRX</th>
                <th width="18%">Nama Anggota</th>
                <th width="22%">Judul Buku</th>
                <th width="12%" class="text-center">Tgl Pinjam</th>
                <th width="12%" class="text-center">Batas Kembali</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%" class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $index => $trx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center"><strong>{{ $trx->kode_transaksi }}</strong></td>
                    <td>
                        {{ $trx->anggota->nama ?? 'Umum / Dihapus' }}<br>
                        <small style="color: #64748b; font-size: 9px;">{{ $trx->anggota->kode_anggota ?? '' }}</small>
                    </td>
                    <td>
                        {{ $trx->buku->judul ?? 'Buku Dihapus' }}<br>
                        <small style="color: #64748b; font-size: 9px;">ISBN: {{ $trx->buku->isbn ?? '-' }}</small>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($trx->tanggal_pinjam)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($trx->tanggal_kembali)->format('d/m/Y') }}
                        @if ($trx->status === 'Dipinjam' && $trx->terlambat > 0)
                            <br><small style="color: #dc2626; font-weight: bold;">(Terlambat {{ $trx->terlambat }}
                                hari)</small>
                        @elseif($trx->status === 'Dikembalikan' && $trx->tanggal_dikembalikan)
                            <br><small style="color: #059669;">(Kembali:
                                {{ \Carbon\Carbon::parse($trx->tanggal_dikembalikan)->format('d/m/Y') }})</small>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($trx->status === 'Dipinjam')
                            @if ($trx->terlambat > 0)
                                <span class="badge badge-terlambat">Terlambat</span>
                            @else
                                <span class="badge badge-dipinjam">Dipinjam</span>
                            @endif
                        @else
                            <span class="badge badge-dikembalikan">Kembali</span>
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- Logika penampilan harga denda per-transaksi --}}
                        @if ($trx->status === 'Dipinjam' && $trx->terlambat > 0)
                            <strong style="color: #dc2626;">Rp
                                {{ number_format($trx->estimasi_denda, 0, ',', '.') }}</strong>
                        @elseif ($trx->status === 'Dikembalikan' && $trx->denda > 0)
                            <strong>Rp {{ number_format($trx->denda, 0, ',', '.') }}</strong>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data transaksi peminjaman yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Verifikasi -->
    <table class="ttd-container">
        <tr>
            <td></td>
            <td>
                <div class="ttd-box">
                    <p>Batang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Kepala Pustakawan,</p>
                    <div class="ttd-space"></div>
                    <p class="ttd-name">Admin Perpustakaan</p>
                    <p style="font-size: 9px; color: #64748b; margin-top: 2px;">NIP. 19930812 202607 1 001</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini sah dikeluarkan oleh Sistem Informasi Manajemen Perpustakaan Batang - Dicetak secara otomatis.
    </div>

</body>

</html>
