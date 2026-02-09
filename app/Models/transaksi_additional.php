<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaksi_additional extends Model
{
    protected $table = 'transaksi_additional';

    protected $fillable = [
        'transaksi_id',
        'additional_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(transaksi::class);
    }

    public function additional()
    {
        return $this->belongsTo(additional::class);
    }
}
