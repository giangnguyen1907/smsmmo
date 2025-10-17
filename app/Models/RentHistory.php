<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentHistory extends Model
{

    protected $table = 'rent_histories'; // Tên bảng trong database
    
    protected $fillable = [
        'user_id',
        'rent_id',
        'sim_number',
        'service_id',
        'network',
        'price',
        'status',
        'rent_date',
        'expire_date',
        'otp_code',
        'messages_received',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'rent_date' => 'datetime',
        'expire_date' => 'datetime',
        'messages_received' => 'array'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_code', 'code');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('expire_date', '<', now());
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Đang chờ',
            'active' => 'Đang hoạt động',
            'completed' => 'Hoàn thành',
            'expired' => 'Hết hạn',
            'cancelled' => 'Đã hủy'
        ];

        return $labels[$this->status] ?? 'Không xác định';
    }
}