<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'tb_province';

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
	
	
    public static function getProvince()
    {
        $query = Province::select('tb_province.*')
        ->orderByRaw('tb_province.id','asc')->get();
        
        return $query;
    }
	/**/
}
