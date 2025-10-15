<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'tb_ward';

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
	
	
    public static function getWard($ps_district_id="")
    {
        $query = Ward::select('tb_ward.*');
		
		if($ps_district_id>0){
			$query = $query -> where('ps_district_id','=',$ps_district_id);
		}
        $query = $query->orderByRaw('tb_ward.id','asc')->get();
        /**/
        return $query;
    }
	/**/
}
