<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'denda',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjam'       => 'date',
        'tanggal_kembali'      => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    // ===== RELATIONSHIPS =====

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // ===== ACCESSORS =====

    /**
     * Durasi peminjaman dalam hari.
     * Jika sudah dikembalikan → hitung dari tgl pinjam ke tgl dikembalikan.
     * Jika masih dipinjam     → hitung dari tgl pinjam sampai hari ini.
     */
    public function getDurasiPeminjamanAttribute()
    {
        if ($this->tanggal_dikembalikan) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_dikembalikan);
        }

        return $this->tanggal_pinjam->diffInDays(now());
    }

    /**
     * Berapa hari terlambat.
     *
     * - Status Dikembalikan : hitung selisih tanggal_kembali vs tanggal_dikembalikan
     * - Status Dipinjam     : hitung selisih tanggal_kembali vs hari ini (realtime)
     * - Tidak terlambat     : return 0
     */
    public function getTerlambatAttribute()
    {
        if ($this->status === 'Dikembalikan') {
            if ($this->tanggal_dikembalikan && $this->tanggal_dikembalikan->gt($this->tanggal_kembali)) {
                return $this->tanggal_kembali->diffInDays($this->tanggal_dikembalikan);
            }
            return 0;
        }

        // Masih dipinjam — cek realtime
        if (now()->startOfDay()->gt($this->tanggal_kembali)) {
            return $this->tanggal_kembali->diffInDays(now()->startOfDay());
        }

        return 0;
    }

    /**
     * Estimasi denda realtime (hanya untuk yang masih berstatus Dipinjam).
     * Untuk yang sudah Dikembalikan, pakai kolom denda di DB (sudah final).
     * Rumus: jumlah hari terlambat × Rp 5.000
     */
    public function getEstimasiDendaAttribute()
    {
        if ($this->status === 'Dikembalikan') {
            return $this->denda; // ambil nilai final dari DB
        }

        return $this->terlambat * 5000;
    }

    /**
     * Badge status HTML siap pakai di view.
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'Dipinjam'
            ? '<span class="badge bg-warning text-dark">Dipinjam</span>'
            : '<span class="badge bg-success">Dikembalikan</span>';
    }

    // ===== SCOPES =====

    /**
     * Scope: transaksi yang masih dipinjam dan sudah melewati tanggal kembali.
     * Dipakai untuk widget "Buku Terlambat" di dashboard.
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'Dipinjam')
                     ->whereDate('tanggal_kembali', '<', now());
    }
}