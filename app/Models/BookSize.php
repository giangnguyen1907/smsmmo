<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookSize extends Model
{
    //
	protected $table = 'tb_book_size';
	
	protected $guarded = [];
	
    protected $casts = [
        //'json_params' => 'object',
    ];
	
}
