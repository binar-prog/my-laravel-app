<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $primaryKey = 'id_kamar';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_kamar',
        'gedung',
        'lantai',
        'tipe',
        'no_kamar',
        'no_bed',
        'status',
    ];

    public function rawatInaps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RawatInap::class, 'id_kamar', 'id_kamar');
    }
}
