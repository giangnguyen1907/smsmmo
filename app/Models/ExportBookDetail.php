<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportBookDetail extends Model
{
    protected $table = 'tb_export_book_detail';
	
	protected $guarded = [];
	
    protected $casts = [];

    public function document() {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
