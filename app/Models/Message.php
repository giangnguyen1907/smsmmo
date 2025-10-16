<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = ['rental_id', 'direction', 'from_number', 'to_number', 'content', 'sent_at', 'status'];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}