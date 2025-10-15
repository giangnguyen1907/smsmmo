<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveReporting extends Model
{
    //
    protected $table = 'tb_live_reporting';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getLiveReportingByParam($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = LiveReporting::select('tb_live_reporting.*')->selectRaw('tb_cms_taxonomys.title as taxonomy_title,tb_cms_taxonomys.url_part as taxonomy_url_part')
        ->leftJoin('tb_cms_taxonomys', 'tb_live_reporting.taxonomy_id', '=', 'tb_cms_taxonomys.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_live_reporting.title', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_live_reporting.id', $params['id']);
        })
        ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
            return $query->where('tb_live_reporting.taxonomy_id', $params['taxonomy_id']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_live_reporting.status', $params['status']);
        });
        
        $query->orderBy('tb_live_reporting.id','desc');
        
        return $query;
    }

}
