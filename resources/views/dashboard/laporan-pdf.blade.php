<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Lengkap Dashboard</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 2px;
            text-align: center;
            text-transform: uppercase;
        }

        .subtitle {
            color: #777;
            margin-bottom: 4px;
            text-align: center;
            font-size: 10px;
        }

        .subtitle.periode {
            margin-bottom: 20px;
            font-weight: bold;
            color: #555;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 6px;
            color: #2c3e50;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 4px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.summary td {
            width: 25%;
            padding: 10px;
            border: 1px solid #ecf0f1;
            text-align: center;
            background-color: #fafafa;
        }

        table.summary .label {
            color: #7f8c8d;
            display: block;
            font-size: 8px;
            text-transform: uppercase;
            margin-bottom: 4px;
            font-weight: bold;
        }

        table.summary .value {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }

        .text-danger {
            color: #e74c3c !important;
        }

        table.data th,
        table.data td {
            border: 1px solid #ecf0f1;
            padding: 6px 8px;
            text-align: left;
        }

        table.data th {
            background: #f8f9fa;
            font-size: 9px;
            color: #34495e;
            text-transform: uppercase;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* ==========================================================
           LAYOUT 2 KOLOM BERSEBELAHAN
           Sengaja pakai <table><tr><td> asli, BUKAN div+float.
           Float pada DomPDF tidak konsisten saat konten kena
           page-break: kolom kiri & kanan bisa terpotong di halaman
           yang berbeda sehingga tidak sejajar lagi. Struktur table
           membuat DomPDF memecah kedua kolom bersama di batas
           halaman yang sama.
        ========================================================== */
        table.layout-2col {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.layout-2col td.layout-2col-cell {
            width: 48%;
            vertical-align: top;
        }

        table.layout-2col td.layout-2col-gap {
            width: 4%;
        }

        .footer-note {
            margin-top: 25px;
            font-size: 8px;
            color: #95a5a6;
            text-align: center;
            border-top: 1px dashed #bdc3c7;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header-title">LAPORAN STATISTIK DASHBOARD PERPUSTAKAAN</div>
    <p class="subtitle">Dicetak pada: {{ now()->translatedFormat('l, d F Y - H:i') }}</p>
    @php
        // Label periode untuk ditampilkan di bawah judul. $period dikirim dari
        // controller (variabel yang sama dipakai untuk filter di dashboard).
        $periodLabels = [
            '7hari' => '7 Hari Terakhir',
            '30hari' => '30 Hari Terakhir',
            '3bulan' => '3 Bulan Terakhir',
            '6bulan' => '6 Bulan Terakhir',
            '1tahun' => '1 Tahun Terakhir',
            'semua' => 'Semua Waktu',
        ];
    @endphp
    @if (isset($period))
        <p class="subtitle periode">Periode Laporan: {{ $periodLabels[$period] ?? 'Semua Waktu' }}</p>
    @endif

    <!-- 1. STATISTIK UTAMA -->
    <div class="section-title">Ringkasan Statistik Utama</div>
    <table class="summary">
        <tr>
            <td>
                <span class="label">Total Buku</span>
                <span class="value">{{ $stats['total_buku'] ?? 0 }}</span>
            </td>
            <td>
                <span class="label">Anggota Aktif</span>
                <span class="value">{{ $stats['total_anggota'] ?? 0 }}</span>
            </td>
            <td>
                <span class="label">Total Transaksi</span>
                <span class="value">{{ $stats['total_transaksi'] ?? 0 }}</span>
            </td>
            <td>
                <span class="label">Sedang Dipinjam</span>
                <span class="value">{{ $stats['sedang_dipinjam'] ?? 0 }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Buku Terlambat</span>
                <span class="value text-danger">{{ $stats['terlambat'] ?? 0 }}</span>
            </td>
            <td>
                <span class="label">Transaksi Hari Ini</span>
                <span class="value">{{ $stats['transaksi_hari_ini'] ?? 0 }}</span>
            </td>
            <td>
                <span class="label">Buku Tersedia</span>
                <span class="value">{{ $stats['buku_tersedia'] ?? 0 }}</span>
            </td>
            <td>
                <span class="label">Denda Bulan Ini</span>
                <span class="value text-danger">Rp
                    {{ number_format($stats['denda_bulan_ini'] ?? 0, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    {{-- ==========================================================
         2. ANALITIK DASHBOARD (representasi tabular dari 4 chart)
         Sumber data: $periodPayload['charts'], variabel yang sama
         persis dipakai untuk inisialisasi Chart.js di dashboard.
         Jika belum dikirim controller ke view PDF, section akan
         menampilkan pesan "belum tersedia" (tidak error).
    ========================================================== */}}

    {{-- 2a & 2b: Kategori Buku | Status Transaksi (bersebelahan)
         Pakai <table><tr><td> asli (bukan float), lihat catatan di
         section "Buku Baru & Anggota Baru" di bawah untuk alasannya. --}}
    @php
        // collect(...)->all() menormalisasi ke array PHP biasa, aman baik
        // sumbernya array asli maupun Illuminate\Support\Collection.
        $kategoriLabels = collect($periodPayload['charts']['kategoriBuku']['labels'] ?? [])->all();
        $kategoriValues = collect($periodPayload['charts']['kategoriBuku']['data'] ?? [])->all();
        $kategoriTotal = array_sum($kategoriValues) ?: 1;

        $statusLabels = collect($periodPayload['charts']['statusTransaksi']['labels'] ?? [])->all();
        $statusValues = collect($periodPayload['charts']['statusTransaksi']['data'] ?? [])->all();
        $statusTotal = $periodPayload['charts']['statusTransaksi']['total'] ?? array_sum($statusValues);
        $statusTotal = $statusTotal ?: 1;
    @endphp
    <table class="layout-2col">
        <tr>
            <td class="layout-2col-cell">
                <div class="section-title">Peminjaman per Kategori Buku</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th width="55%">Kategori</th>
                            <th width="20%" style="text-align:center;">Jumlah</th>
                            <th width="25%" style="text-align:center;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($kategoriLabels) > 0)
                            @foreach ($kategoriLabels as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td style="text-align:center;">{{ $kategoriValues[$i] ?? 0 }}</td>
                                    <td style="text-align:center;">
                                        {{ round(($kategoriValues[$i] ?? 0) / $kategoriTotal * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="text-align:center; color:#999;">Data belum tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>

            <td class="layout-2col-gap">&nbsp;</td>

            <td class="layout-2col-cell">
                <div class="section-title">Status Transaksi</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th width="55%">Status</th>
                            <th width="20%" style="text-align:center;">Jumlah</th>
                            <th width="25%" style="text-align:center;">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($statusLabels) > 0)
                            @foreach ($statusLabels as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td style="text-align:center;">{{ $statusValues[$i] ?? 0 }}</td>
                                    <td style="text-align:center;">
                                        {{ round(($statusValues[$i] ?? 0) / $statusTotal * 100, 1) }}%</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" style="text-align:center; color:#999;">Data belum tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- 2c: Ringkasan Trend Peminjaman -->
    <div class="section-title">Ringkasan Trend Peminjaman</div>
    @php
        $trendPinjam = collect($periodPayload['charts']['trend']['pinjam'] ?? [])->all();
        $jumlahTitik = count($trendPinjam) ?: 1;
        $totalTrend = array_sum($trendPinjam);
        $rataRata = round($totalTrend / $jumlahTitik, 1);
        $tertinggi = count($trendPinjam) ? max($trendPinjam) : 0;
        $terendah = count($trendPinjam) ? min($trendPinjam) : 0;

        // Bandingkan rata-rata paruh pertama vs paruh kedua untuk arah trend
        $half = intdiv(count($trendPinjam), 2);
        $firstHalf = array_slice($trendPinjam, 0, $half ?: count($trendPinjam));
        $secondHalf = array_slice($trendPinjam, $half);
        $firstAvg = count($firstHalf) ? array_sum($firstHalf) / count($firstHalf) : 0;
        $secondAvg = count($secondHalf) ? array_sum($secondHalf) / count($secondHalf) : 0;
        $trendDiff = $firstAvg > 0 ? round((($secondAvg - $firstAvg) / $firstAvg) * 100, 1) : ($secondAvg > 0 ? 100 : 0);
    @endphp
    @if (count($trendPinjam) > 0)
        <table class="summary">
            <tr>
                <td>
                    <span class="label">Total Peminjaman</span>
                    <span class="value">{{ $totalTrend }}</span>
                </td>
                <td>
                    <span class="label">Rata-rata</span>
                    <span class="value">{{ $rataRata }}</span>
                </td>
                <td>
                    <span class="label">Tertinggi</span>
                    <span class="value">{{ $tertinggi }}</span>
                </td>
                <td>
                    <span class="label">Terendah</span>
                    <span class="value">{{ $terendah }}</span>
                </td>
            </tr>
        </table>
        <p style="text-align:center; font-size:9px; color:#555;">
            Perubahan dibanding awal periode:
            <strong class="{{ $trendDiff < 0 ? 'text-danger' : '' }}">{{ $trendDiff >= 0 ? '+' : '' }}{{ $trendDiff }}%</strong>
        </p>
    @else
        <p style="text-align:center; color:#999;">Data trend peminjaman belum tersedia.</p>
    @endif

    <!-- 2d. DATA BUKU POPULER -->
    <div class="section-title">Top 5 Buku Terpopuler (Berdasarkan Peminjaman)</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="50%">Judul Buku</th>
                <th width="30%">Pengarang</th>
                <th width="15%" style="text-align: center;">Jumlah Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($bukuPopuler) && count($bukuPopuler) > 0)
                @foreach ($bukuPopuler as $buku)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ optional($buku->pengarang)->nama ?? ($buku->pengarang ?? '-') }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $buku->transaksis_count }}x</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align:center; color:#999;">Belum ada data peminjaman buku.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- 3. TRANSAKSI TERBARU -->
    <div class="section-title">5 Transaksi Terbaru</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="25%">Anggota</th>
                <th width="30%">Buku</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($recentTransaksi) && count($recentTransaksi) > 0)
                @foreach ($recentTransaksi as $trx)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td>{{ $trx->anggota->nama }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($trx->buku->judul, 35) }}</td>
                        <td>{{ $trx->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>
                            @if ($trx->status == 'Dipinjam')
                                <span class="badge badge-warning">Dipinjam</span>
                            @else
                                <span class="badge badge-success">Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" style="text-align:center; color:#999;">Belum ada data transaksi</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- 4. DATA BARU (BUKU & ANGGOTA) DIBUAT BERSEBELAHAN
         Catatan: dipakai <table><tr><td> asli (BUKAN float col-half),
         karena float pada DomPDF tidak konsisten saat kena page-break —
         kolom kiri/kanan bisa terpotong di halaman berbeda dan jadi
         tidak sejajar. Struktur table membuat DomPDF memecah kedua
         kolom bersamaan di batas halaman yang sama. --}}
    <table class="layout-2col">
        <tr>
            <!-- Kolom Kiri: Buku Baru -->
            <td class="layout-2col-cell">
                <div class="section-title">Buku Baru Ditambahkan</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th width="75%">Judul Buku</th>
                            <th width="25%" style="text-align: center;">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($bukuTerbaru) && count($bukuTerbaru) > 0)
                            @foreach ($bukuTerbaru as $buku)
                                <tr>
                                    <td>
                                        <strong>{{ \Illuminate\Support\Str::limit($buku->judul, 25) }}</strong><br>
                                        <span style="font-size: 8px; color: #7f8c8d;">Pengarang:
                                            {{ optional($buku->pengarang)->nama ?? ($buku->pengarang ?? '-') }}</span>
                                    </td>
                                    <td style="text-align: center;">{{ $buku->stok }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999;">Belum ada buku baru</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>

            <td class="layout-2col-gap">&nbsp;</td>

            <!-- Kolom Kanan: Anggota Baru -->
            <td class="layout-2col-cell">
                <div class="section-title">Anggota Baru Terdaftar</div>
                <table class="data">
                    <thead>
                        <tr>
                            <th width="75%">Nama Anggota</th>
                            <th width="25%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($anggotaTerbaru) && count($anggotaTerbaru) > 0)
                            @foreach ($anggotaTerbaru as $anggota)
                                <tr>
                                    <td>
                                        <strong>{{ \Illuminate\Support\Str::limit($anggota->nama, 25) }}</strong><br>
                                        <span style="font-size: 8px; color: #7f8c8d;">ID:
                                            {{ $anggota->id_anggota ?? ($anggota->nomor_anggota ?? ($anggota->kode_anggota ?? $anggota->id)) }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $anggota->status === 'Aktif' ? 'badge-success' : 'badge-danger' }}">{{ $anggota->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" style="text-align:center; color:#999;">Belum ada anggota baru</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- 5. BUKU TERLAMBAT -->
    <div class="section-title" style="margin-top: 15px;">Daftar Buku Terlambat (Belum Dikembalikan)</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Buku</th>
                <th width="30%">Peminjam</th>
                <th width="15%">Jatuh Tempo</th>
                <th width="15%">Keterlambatan</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($listTerlambat) && count($listTerlambat) > 0)
                @foreach ($listTerlambat as $terlambat)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $terlambat->buku->judul }}</td>
                        <td>{{ $terlambat->anggota->nama }}</td>
                        <td>{{ $terlambat->tanggal_kembali->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge badge-danger">{{ now()->diffInDays($terlambat->tanggal_kembali) }}
                                Hari</span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align:center; color:#999;">Tidak ada buku terlambat saat ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p class="footer-note">
        Dokumen ini adalah hasil generate otomatis dari Sistem Perpustakaan.<br>
        <i>*Representasi grafis (Chart) ditampilkan dalam format data tabular untuk optimalisasi cetak.</i>
    </p>
</body>

</html>