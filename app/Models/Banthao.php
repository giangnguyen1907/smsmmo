<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banthao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cms_banthao';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function getCmsBanthao($params, $isPaginate = false)
    {
        $query = Banthao::selectRaw('tb_cms_banthao.*,
                    a.name as admin_created,
                    b.name as admin_updated')
        ->leftJoin('admins as a', 'a.id', '=', 'tb_cms_banthao.admin_created_id')
        ->leftJoin('admins as b', 'b.id', '=', 'tb_cms_banthao.admin_updated_id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_cms_banthao.tacpham', 'like', '%' . $keyword . '%')
                    ->orWhere('tb_cms_banthao.tacgia', 'like', '%' . $keyword . '%')
                    ->orWhere('tb_cms_banthao.noidung', 'like', '%' . $keyword . '%')
                    ->orWhere('tb_cms_banthao.dienthoai', 'like', '%' . $keyword . '%');
            });
        });
			
		if(isset($params['status'])) {
            $query->where('tb_cms_banthao.status', $params['status']);
        }

        if(isset($params['theloai'])) {
            $query->where('tb_cms_banthao.theloai', $params['theloai']);
        }

        if(isset($params['from_date']) && isset($params['to_date'])) {
            $fromDate = date("Y-m-d", strtotime($params['from_date']));
            $toDate = date("Y-m-d", strtotime($params['to_date']));
            $query->where(function ($query) use ($fromDate, $toDate) {
                $query->whereDate('created_at', '>=', $fromDate)
                      ->whereDate('created_at', '<=', $toDate);
            });
        }

        $query->orderByRaw('tb_cms_banthao.id DESC');
        
        return $query;
    }

    public function nguoisua()
    {
        return $this->belongsTo(Admin::class, 'nguoichinhsua');
    }
}
