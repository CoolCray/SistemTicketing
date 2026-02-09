<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'email',
        'no_telp',
        'nama_siswa',
        'nis',
    ];
}
