<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $primaryKey = 'id_dokter';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_dokter',
        'no_kualifikasi',
        'nama_dokter',
        'jenis_kelamin',
        'nama_poli',
        'spesialisasi',
        'no_telepon',
        'tanggal_masuk',
        'hari_praktik',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'jam_mulai' => 'string',
        'jam_selesai' => 'string',
    ];

    public function kunjungans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_dokter', 'id_dokter');
    }

    public function poli(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Poli::class, 'nama_poli', 'nama_poli');
    }

    public function pemeriksaanLanjutans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PemeriksaanLanjutan::class, 'id_dokter', 'id_dokter');
    }
}
