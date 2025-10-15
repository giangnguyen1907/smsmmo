<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'tb_district';

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
    protected $casts = [];
	
	
    public static function getDistrict($ps_province_id="")
    {
        $query = District::select('tb_district.*');
		
		if($ps_province_id>0){
			$query = $query -> where('ps_province_id','=',$ps_province_id);
		}
        $query = $query->orderByRaw('tb_district.id','asc')->get();
        /**/
        return $query;
    }
	/**/
}
