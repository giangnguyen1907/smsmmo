<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Consts;

class CmsDepartment extends Model
{
    protected $table = 'tb_cms_department';

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

    public static function getCmsDepartment($params, $isPaginate = false)
    {
        $query = CmsDepartment::select('tb_cms_department.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_department.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_department.id', $params['id']);
            })
           ;
        if (!empty($params['status'])) {
            $query->where('tb_cms_department.status', $params['status']);
        } else {
            $query->where('tb_cms_department.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_department.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_department.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_department.id ASC');
        }

        return $query;
    }
}
