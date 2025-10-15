<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsFileProject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_file_project';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
