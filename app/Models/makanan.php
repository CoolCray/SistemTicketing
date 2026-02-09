<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class makanan extends Model
{
    protected $table ="makanan";

    protected $fillable = [
        'nama_makanan',
    ];
}
