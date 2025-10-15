<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPostDichvu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_posts_dichvu';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'json_params' => 'object',
        'title' => 'object',
        'brief' => 'object',
        'content' => 'object',
    ];
	
}
