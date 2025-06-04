<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawatInap extends Model
{
    protected $primaryKey = 'id_rawat';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_rawat',
        'id_pasien',
        'id_kamar',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function ($rawatInap) {
            if ($rawatInap->tanggal_keluar === null) {
                Kamar::where('id_kamar', $rawatInap->id_kamar)
                    ->update(['status' => 'terisi']);
            } else {
                Kamar::where('id_kamar', $rawatInap->id_kamar)
                    ->update(['status' => 'kosong']);
            }
        });
    }

    public function pasien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function kamar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
