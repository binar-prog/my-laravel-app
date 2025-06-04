<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $primaryKey = 'id_tagihan';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_tagihan',
        'id_pasien',
        'tanggal',
        'biaya_kunjungan',
        'biaya_pemeriksaan',
        'biaya_obat',
        'total_biaya',
        'metode_pembayaran',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pasien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    // Tambahkan accessor untuk format Rupiah
    public function getTotalBiayaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_biaya, 2, ',', '.');
    }
}
