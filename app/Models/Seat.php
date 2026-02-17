<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';
    protected $fillable = [
        'seat_number',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
