<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['customer_id', 'package_id', 'seat_id', 'additional_id'];

    public function seats()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function packages()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function transaction_additionals()
    {
        return $this->belongsToMany(Additional::class, 'transaction_additionals', 'transaction_id', 'additional_id')
            ->withPivot('jumlah');
    }
}
