<?php

namespace App\Models;

use App\Models\Transaction_Additional;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{

    protected $table = 'additionals';

    protected $fillable = ['name', 'price', 'total_seats'];

    public function transaction_additionals()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_additionals', 'additional_id', 'transaction_id')
            ->withPivot('jumlah');
    }
}
