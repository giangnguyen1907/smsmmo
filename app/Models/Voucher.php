<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    
    protected $table = 'tb_voucher';

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
    
    
    public static function getVoucher($code, $date_at)
    {
        return Voucher::select('tb_voucher.*')
            ->where('code', $code)
            ->where('status', 1)
            ->where(function($query) use ($date_at) {
                $formattedDate = date('YmdHis', strtotime($date_at));
                $query->whereNull('start_date')
                    ->orWhereRaw('DATE_FORMAT(start_date, "%Y%m%d%H%i%s") <= ?', [$formattedDate]);
            })
            ->where(function($query) use ($date_at) {
                $formattedDate = date('YmdHis', strtotime($date_at));
                $query->whereNull('end_date')
                    ->orWhereRaw('DATE_FORMAT(end_date, "%Y%m%d%H%i%s") >= ?', [$formattedDate]);
            })
            ->first();
    }


    public static function getVoucherId($id, $date_at)
    {
        return Voucher::select('tb_voucher.*')
            ->where('id', $id)
            ->where('status', 1)
            ->where(function($query) use ($date_at) {
                $formattedDate = date('YmdHis', strtotime($date_at));
                $query->whereNull('start_date')
                    ->orWhereRaw('DATE_FORMAT(start_date, "%Y%m%d%H%i%s") <= ?', [$formattedDate]);
            })
            ->where(function($query) use ($date_at) {
                $formattedDate = date('YmdHis', strtotime($date_at));
                $query->whereNull('end_date')
                    ->orWhereRaw('DATE_FORMAT(end_date, "%Y%m%d%H%i%s") >= ?', [$formattedDate]);
            })
            ->first();
    }

}
