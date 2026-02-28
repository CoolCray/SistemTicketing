<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $table = 'drinks';

    protected $fillable = ['name'];

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }
}
