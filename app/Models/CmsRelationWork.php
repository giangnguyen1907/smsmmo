<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsRelationWork extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_relation_work';

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

    // lấy ra danh sách lịch sử thao tác từ bảng CmsHistoryWork, với id.CmsRelationWork = relation_work_id.CmsHistoryWork
    public function historyWorks()
    {
        return $this->hasMany(CmsHistoryWork::class, 'relation_work_id');
    }

    // lấy ra công việc cha, với manage_work_id.CmsRelationWork = id.CmsManageWork
    public function manageWork()
    {
        return $this->belongsTo(CmsManageWork::class, 'manage_work_id');
    }
	
	public function fileRelation()
    {
        return $this->hasMany(FileRelation::class, 'relation_work_id')->where('type', 'relation')->where('status','active');
    }
}
