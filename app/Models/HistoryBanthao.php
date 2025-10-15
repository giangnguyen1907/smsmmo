<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryBanthao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_history_banthao';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_created_id');
    }
}
