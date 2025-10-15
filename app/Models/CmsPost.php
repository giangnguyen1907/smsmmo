<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPost extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_posts';

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
        'title' => 'object',
        'brief' => 'object',
        'content' => 'object',
    ];

    public function cmsComments() {
        return $this->hasMany(Comment::class, 'post_id', 'id')->where('is_type', 'news');
    }
	
	public static function danhSachTinTuc($params)
    {
		//dd($params);
        //echo "AAAAAAAAA".$params['status'];die;
        $query = CmsPost::select('tb_cms_posts.*')
		->selectRaw('tb_cms_taxonomys.title as taxonomy_title,tb_cms_taxonomys.url_part as taxonomy_url_part')
        ->leftJoin('tb_cms_taxonomys', 'tb_cms_posts.taxonomy_id', '=', 'tb_cms_taxonomys.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%');
            });
        });
		
        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        $query->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_cms_posts.id', $params['id']);
        });
        $query->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
            return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
        });
        $query->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_cms_posts.status', $params['status']);
        });
		if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
		
        return $query;
    }
	
}
