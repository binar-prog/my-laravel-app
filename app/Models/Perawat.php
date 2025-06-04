<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawat extends Model
{
    protected $primaryKey = 'id_perawat';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_perawat',
        'no_kualifikasi',
        'nama_perawat',
        'jenis_kelamin',
        'nama_poli',
        'no_telepon',
        'tanggal_masuk',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function poli(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Poli::class, 'nama_poli', 'nama_poli');
    }

}
