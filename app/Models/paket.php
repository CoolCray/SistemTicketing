<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paket extends Model
{
    protected $table = 'paket';

    protected $fillable = [
        'nama_paket',
        'makanan_id',
        'minuman_id',
        'additional_id',
        'harga',
    ];

    public function makanan()
    {
        return $this->belongsTo(makanan::class);
    }

    public function minuman()
    {
        return $this->belongsTo(minuman::class);
    }

    public function additional()
    {
        return $this->belongsTo(additional::class);
    }
}
