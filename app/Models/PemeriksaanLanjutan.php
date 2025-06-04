<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanLanjutan extends Model
{
    protected $primaryKey = 'id_pemeriksaan';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_pemeriksaan',
        'id_pasien',
        'id_dokter',
        'tanggal_pemeriksaan',
        'jenis_pemeriksaan',
        'hasil_pemeriksaan',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
    ];

    public function dokter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    public function pasien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }
}
