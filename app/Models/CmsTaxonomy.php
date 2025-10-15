<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsTaxonomy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_taxonomys';

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
		'brief' => 'object',
        'json_params' => 'object',
        'title' => 'object',
        
    ];

    public function childMenus() {
        return $this->hasMany(CmsTaxonomy::class, 'parent_id', 'id');
    }
}
