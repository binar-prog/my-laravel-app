<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    protected $primaryKey = 'id_staf';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_staf',
        'nama_staf',
        'jenis_kelamin',
        'jabatan',
        'unit_kerja',
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
