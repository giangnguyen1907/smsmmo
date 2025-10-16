<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{

    protected $fillable = ['number', 'provider_id', 'prefix', 'status', 'last_used_at'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
