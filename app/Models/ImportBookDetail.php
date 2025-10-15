<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportBookDetail extends Model
{
    protected $table = 'tb_import_book_detail';
	
	protected $guarded = [];
	
    protected $casts = [];

    public function importBook() {
        return $this->belongsTo(ImportBook::class, 'import_id', 'id');
    }

    public function document() {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
