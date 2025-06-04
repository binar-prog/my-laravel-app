<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $primaryKey = 'id_kunjungan';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_kunjungan',
        'id_pasien',
        'id_dokter',
        'nama_poli',
        'id_peskripsi',
        'tanggal_jam',
        'diagnosis',
    ];

    protected $casts = [
        'tanggal_jam' => 'datetime'
    ];

    public function poli() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Poli::class, 'nama_poli', 'nama_poli');
    }
    public function dokter() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    public function pasien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function preskripsi(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Preskripsi::class, 'id_kunjungan');
    }
}
