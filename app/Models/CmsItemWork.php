<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Consts;

class CmsItemWork extends Model
{
    protected $table = 'tb_item_work';

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
    protected $casts = []
    ;

    public static function getCmsItemWork($params, $isPaginate = false)
    {
        $query = CmsItemWork::select('tb_item_work.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_item_work.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_item_work.id', $params['id']);
            })
           ;
        if (!empty($params['status'])) {
            $query->where('tb_item_work.status', $params['status']);
        } else {
            $query->where('tb_item_work.status', "!=", Consts::STATUS_DELETE);
        }

        $query->orderByRaw('tb_item_work.id ASC');

        return $query;
    }
}
