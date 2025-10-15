<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineExchange extends Model
{
    protected $table = 'tb_online_exchange';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getOnlineExchangeByParam($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = OnlineExchange::select('tb_online_exchange.*')
        ->selectRaw('tb_cms_taxonomys.title as taxonomy_title,tb_cms_taxonomys.url_part as taxonomy_url_part')
        ->leftJoin('tb_cms_taxonomys', 'tb_online_exchange.taxonomy_id', '=', 'tb_cms_taxonomys.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_online_exchange.title', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_online_exchange.id', $params['id']);
        })
        ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
            return $query->where('tb_online_exchange.taxonomy_id', $params['taxonomy_id']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_online_exchange.status', $params['status']);
        });
        
        $query->orderBy('tb_online_exchange.id','desc');
        
        return $query;
    }

}
