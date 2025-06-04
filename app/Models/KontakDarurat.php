<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakDarurat extends Model
{
    protected $table = 'kontak_darurats';
    protected $primaryKey = 'id_kontak';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kontak',
        'nama_kontak',
        'no_darurat',
        'hubungan',
        'id_pasien',
    ];

    public function pasien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }
}
