<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsHistoryWork extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_history_work';

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
    protected $casts = [];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }
	
	public function relationWork()
    {
        return $this->belongsTo(CmsRelationWork::class, 'relation_work_id');
    }
	
	public function fileRelationHis()
    {
        return $this->hasMany(FileRelation::class, 'relation_work_id')->where('type', 'his')->where('status','active');
    }

}
