<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function packages()
    {
        return $this->belongsTo(Package::class);
    }

    public function transaction_additionals()
    {
        return $this->belongsToMany(Transaction_Additional::class);
    }

    
}
