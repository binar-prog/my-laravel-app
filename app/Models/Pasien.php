<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $primaryKey = 'id_pasien';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_pasien',
        'nik',
        'nama_pasien',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'status_asuransi',
        'id_kontak'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kontakDarurat(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KontakDarurat::class, 'id_kontak', 'id_kontak');
    }

    public function kunjungans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_pasien', 'id_pasien');
    }

    public function preskripsis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Preskripsi::class, 'id_pasien', 'id_pasien');
    }

    public function tagihans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tagihan::class, 'id_pasien', 'id_pasien');
    }

    public function rawatInaps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RawatInap::class, 'id_pasien', 'id_pasien');
    }

    public function pemeriksaanLanjuts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PemeriksaanLanjutan::class, 'id_pasien', 'id_pasien');
    }

}
