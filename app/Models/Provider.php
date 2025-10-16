<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['name', 'country_code', 'is_vip'];

    public function phoneNumbers()
    {
        return $this->hasMany(PhoneNumber::class);
    }
}