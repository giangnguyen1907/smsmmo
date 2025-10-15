<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportBook extends Model
{
    //
	protected $table = 'tb_export_book';
	
	protected $guarded = [];
	
    protected $casts = [];

    public function bill() {
        return $this->belongsTo(ListBill::class, 'bill_id', 'id');
    }

    public function workShop() {
        return $this->belongsTo(ManagerShop::class, 'workshop', 'id');
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function exportBookDetails() {
        return $this->hasMany(ExportBookDetail::class, 'export_id', 'id');
    }

    public function scopeWithDetails($query) {
        return $query->with([
            'exportBookDetails' => function ($query) {
                $query->select('id', 'export_id', 'document_id', 'quantity', 'total');
            }, 'exportBookDetails.document' => function ($query) {
                $query->select('id', 'title', 'cost', 'alias')->where('status', 1);
            }, 'bill' => function ($query) {
                $query->select('id', 'title');
            }, 'workShop' => function ($query) {
                $query->select('id', 'title', 'phone', 'address');
            }, 'customer' => function ($query) {
                $query->select('id','name', 'address', 'json_params')->where('status', 'active');
            }
        ]);
    }
}
