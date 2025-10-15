<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleFunction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_module_functions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
