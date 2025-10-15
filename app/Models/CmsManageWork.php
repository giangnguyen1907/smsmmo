<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsManageWork extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_manage_work';

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
        'task' => 'object',
    ];

    public function relationWorks()
    {
        return $this->hasMany(CmsRelationWork::class, 'manage_work_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_updated_id');
    }
}
