<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsManageProject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_manage_project';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }
}
