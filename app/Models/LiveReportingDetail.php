<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveReportingDetail extends Model
{
    protected $table = 'tb_live_reporting_detail';
    protected $guarded = [];
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getLiveReportingDetail($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = LiveReportingDetail::select('tb_live_reporting_detail.*')
        ->selectRaw('tb_live_reporting.title as live_title, admins.name as admin_name, admins.avatar as avatar')
        ->leftJoin('tb_live_reporting', 'tb_live_reporting_detail.live_id', '=', 'tb_live_reporting.id')
        ->leftJoin('admins', 'tb_live_reporting_detail.admin_updated_id', '=', 'admins.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_live_reporting_detail.content', 'like', '%' . $keyword . '%')
                ->orWhere('tb_live_reporting.title', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_live_reporting_detail.id', $params['id']);
        })
        ->when(!empty($params['live_id']), function ($query) use ($params) {
            return $query->where('tb_live_reporting_detail.live_id', $params['live_id']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_live_reporting_detail.status', $params['status']);
        });
        
        $query->orderBy('tb_live_reporting_detail.id','asc')
        ;
        
        return $query;
    }

}
