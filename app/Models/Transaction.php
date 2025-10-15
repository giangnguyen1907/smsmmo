<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
	protected $table = 'tb_transaction';
	
	protected $guarded = [];
	
    protected $casts = [];
}
