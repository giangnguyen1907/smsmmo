<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsProduct extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_products';

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
	
	public static function danhSachSanPham($params)
    {
		//dd($params);
        //echo "AAAAAAAAA".$params['status'];die;
        $query = CmsProduct::select('tb_products.*')
		->selectRaw('tb_cms_taxonomys.title as taxonomy_title,tb_cms_taxonomys.url_part as taxonomy_url_part')
        ->leftJoin('tb_cms_taxonomys', 'tb_products.taxonomy_id', '=', 'tb_cms_taxonomys.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_products.title', 'like', '%' . $keyword . '%');
            });
        });
		
		if($params['hienthi']!=''){
			return $query->where('tb_products.hienthi','like', '%;' . $params['hienthi'] . ';%');
		}
		
        $query->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_products.id', $params['id']);
        });
        $query->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
            return $query->where('tb_products.taxonomy_id', $params['taxonomy_id']);
        });
        $query->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_products.status', $params['status']);
        });
		$query->when(!empty($params['tinhtrang']), function ($query) use ($params) {
            return $query->where('tb_products.tinhtrang', $params['tinhtrang']);
        });
        
        $query->orderBy('tb_products.id','desc');
        
        return $query;
    }
	
}
