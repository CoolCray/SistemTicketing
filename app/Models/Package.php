<?php

namespace App\Models;

use App\Models\Food;
use App\Models\Drink;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'name', 
        'price', 
        'total_seats',
        'food_id',
        'drink_id'
    ];
    
    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function drinks()
    {
        return $this->hasMany(Drink::class);
    }
}
