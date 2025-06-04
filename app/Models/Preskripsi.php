<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preskripsi extends Model
{
    protected $primaryKey = 'id_preskripsi';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_peskripsi',
        'id_pasien',
        'id_obat',
        'tanggal',
        'dosis',
        'jumlah',
        'cara_penggunaan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pasien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function obats()
    {
        return $this->belongsToMany(Obat::class, 'preskripsi_obat', 'id_preskripsi', 'id_obat')
            ->withPivot('dosis', 'jumlah', 'cara_penggunaan');
    }

    public function kunjungan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kunjungan::class, 'id_kunjungan');
    }
}
