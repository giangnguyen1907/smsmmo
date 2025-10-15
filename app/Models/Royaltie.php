<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Royaltie extends Model
{
    protected $table = 'tb_royaltie';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $casts = [];

    public static function getRoyaltieByParams($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = Royaltie::select('tb_royaltie.*')
        ->selectRaw('tb_cms_posts.title as post_title,tb_cms_posts.aproved_date as aproved_date,tb_cms_posts.author as author, admins.name as admin_name')
        ->leftJoin('tb_cms_posts', 'tb_royaltie.post_id', '=', 'tb_cms_posts.id')
        ->leftJoin('admins', 'tb_royaltie.admin_created_id', '=', 'admins.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_royaltie.note', 'like', '%' . $keyword . '%')
                ->orWhere('tb_cms_posts.title', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['from_date']), function ($query) use ($params) {
            return $query->whereRaw('DATE_FORMAT(tb_royaltie.created_at,"%Y%m%d") >= '.date("Ymd",strtotime($params['from_date'])));
        })
        ->when(!empty($params['to_date']), function ($query) use ($params) {
            return $query->whereRaw('DATE_FORMAT(tb_royaltie.created_at,"%Y%m%d") <='.date("Ymd",strtotime($params['to_date'])));
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_royaltie.id', $params['id']);
        })
        ->when(!empty($params['post_id']), function ($query) use ($params) {
            return $query->where('tb_royaltie.post_id', $params['post_id']);
        });
        if(isset($params['status']) and $params['status'] >= 0){
            return $query->where('tb_royaltie.status', $params['status']);
        }
        $query->orderBy('tb_royaltie.id','desc')
        ;
        
        return $query;
    }
}
