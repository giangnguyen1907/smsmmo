<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportBook extends Model
{
    //
	protected $table = 'tb_import_book';
	
	protected $guarded = [];
	
    protected $casts = [];

    public function bill()
    {
        return $this->belongsTo(ListBill::class, 'bill_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function workShop()
    {
        return $this->belongsTo(ManagerShop::class, 'workshop', 'id');
    }

    public function importBookDetails() {
        return $this->hasMany(ImportBookDetail::class, 'import_id', 'id');
    }

    public function documents() {
        return $this->hasManyThrough(
            Document::class,
            ImportBookDetail::class,
            'import_id', //khóa ngoại của bảng trung gian
            'id', //khóa chính của bảng đích (documents)
            'id', //khóa chính của bảng gốc (importBook)
            'document_id' //khóa ngoại của bảng đích (document)
        );
    }

    public function scopeWithDetails($query) {
        return $query->with(['importBookDetails' => function ($query) {
            $query->select('id','import_id', 'document_id','quantity', 'cost');
        },'importBookDetails.document' => function ($query) {
			$query->select('id', 'title','image', 'cost');
		}, 'bill' => function ($query) {
			$query->select('id','title', 'is_type');
		}, 'customer' => function ($query) {
			$query->select('id','name', 'address', 'json_params');
		}, 'workShop' => function ($query) {
			$query->select('id', 'title', 'phone', 'address');
		}]);
    }
}
