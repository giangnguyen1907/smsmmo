<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = ['user_id', 'phone_number_id', 'service_id', 'start_time', 'end_time', 'status', 'cost', 'error_message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phoneNumber()
    {
        return $this->belongsTo(PhoneNumber::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}