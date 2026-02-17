<?php

namespace App\Models;

use App\Models\Transaction_Additional;
use Illuminate\Database\Eloquent\Model;

class Additional extends Model
{

    protected $table = 'additionals';

    protected $fillable = ['name', 'price'];

    public function transaction_additionals()
    {
        return $this->belongsToMany(transaction_additional::class);
    }
}
