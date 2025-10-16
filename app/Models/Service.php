<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'price_per_unit', 'duration_minutes'];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}