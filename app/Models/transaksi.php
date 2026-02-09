<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'pelanggan_id',
        'kursi_id',
        'paket_id',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(pelanggan::class);
    }

    public function kursi()
    {
        return $this->belongsTo(kursi::class);
    }

    public function paket()
    {
        return $this->belongsTo(paket::class);
    }
}
