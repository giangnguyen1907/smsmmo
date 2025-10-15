<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'tb_document';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
	protected $casts = [
        'json_params' => 'object'
        
    ];

    public function cmsComments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id')->where('is_type', 'document');
    }
    
    public function historyBuyebooks()
    {
        return $this->hasMany(CmsHistoryBuyebook::class, 'document_id');
    }
}
