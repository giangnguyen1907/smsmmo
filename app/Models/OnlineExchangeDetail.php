<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineExchangeDetail extends Model
{
    protected $table = 'tb_online_exchange_detail';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getOnlineExchangeDetailByParams($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = OnlineExchangeDetail::select('tb_online_exchange_detail.*')
        ->selectRaw('tb_online_exchange.title as live_title, admins.name as admin_name, admins.avatar as avatar, tb_experts.title as experts_name, tb_experts.image as experts_image, b.content as reply_comment')
        ->leftJoin('tb_online_exchange', 'tb_online_exchange_detail.exchange_id', '=', 'tb_online_exchange.id')
        ->leftJoin('tb_experts', 'tb_online_exchange_detail.experts_id', '=', 'tb_experts.id')
        ->leftJoin('admins', 'tb_online_exchange_detail.admin_updated_id', '=', 'admins.id')
        ->leftJoin('tb_online_exchange_detail as b', 'tb_online_exchange_detail.parent_id', '=', 'b.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_online_exchange_detail.content', 'like', '%' . $keyword . '%')
                ->orWhere('tb_online_exchange.title', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_online_exchange_detail.id', $params['id']);
        })
        ->when(!empty($params['exchange_id']), function ($query) use ($params) {
            return $query->where('tb_online_exchange_detail.exchange_id', $params['exchange_id']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_online_exchange_detail.status', $params['status']);
        });
        
        $query->orderBy('tb_online_exchange_detail.id','asc')
        ;
        
        return $query;
    }

}
