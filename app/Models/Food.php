<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';
    protected $fillable = ['name'];

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }
}
