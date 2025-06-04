<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $primaryKey = 'nama_poli';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'nama_poli',
        'jumlah_dokter',
        'jumlah_perawat',
        'jumlah_staf',
    ];

    public function dokters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Dokter::class, 'nama_poli', 'nama_poli');
    }

    public function perawats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Perawat::class, 'nama_poli', 'nama_poli');
    }

    public function stafs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Staf::class, 'nama_poli', 'nama_poli');
    }

    public function kunjungans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Kunjungan::class, 'nama_poli', 'nama_poli');
    }
}
