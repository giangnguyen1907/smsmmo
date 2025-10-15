<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'tb_post_history';

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
    protected $casts = [
        'json_params' => 'object',
    ];

    public static function getHistoryByParams($params)
    {
        $query = History::select('tb_post_history.*')
        ->selectRaw('tb_cms_posts.title as post_title,admins.name as admins_name')
        ->leftJoin('tb_cms_posts', 'tb_post_history.post_id', '=', 'tb_cms_posts.id')
        ->leftJoin('admins', 'tb_post_history.admin_updated_id', '=', 'admins.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_post_history.title', 'like', '%' . $keyword . '%')
                ->orWhere('tb_post_history.brief', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_post_history.id', $params['id']);
        })
        ->when(!empty($params['post_id']), function ($query) use ($params) {
            return $query->where('tb_post_history.post_id', $params['post_id']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_post_history.status', $params['status']);
        })
        ->orderBy('tb_post_history.id','desc')
        ;
        
        return $query;
    }

    public static function getHistoryByPostId($params)
    {
        $query = History::select('tb_post_history.*')
        ->selectRaw('tb_cms_posts.title as post_title,admins.name as admins_name')
        ->leftJoin('tb_cms_posts', 'tb_post_history.post_id', '=', 'tb_cms_posts.id')
        ->leftJoin('admins', 'tb_post_history.admin_updated_id', '=', 'admins.id')
        
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_post_history.id', '<' ,$params['id']);
        })
        ->when(!empty($params['post_id']), function ($query) use ($params) {
            return $query->where('tb_post_history.post_id', $params['post_id']);
        })
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_post_history.status', $params['status']);
        })
        ->orderBy('tb_post_history.id','desc')
        ;
        
        return $query;
    }
}

