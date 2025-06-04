<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $primaryKey = 'id_obat';
    public $incrementing = false; // karena bukan auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_obat',
        'nama_obat',
        'stok',
        'harga_satuan',
        'jenis',
    ];

    public function preskripsis()
    {
        return $this->belongsToMany(Preskripsi::class, 'preskripsi_obat', 'id_obat', 'id_preskripsi')
            ->withPivot('dosis', 'jumlah', 'cara_penggunaan');
    }
}
