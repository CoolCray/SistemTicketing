<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class Transaction_Additional extends Model
{
    protected $table = 'transaction_additionals';

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class);
    }

    public function additionals()
    {
        return $this->belongsToMany(Additional::class);
    }
}
