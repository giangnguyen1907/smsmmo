<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbViews extends Model
{
    protected $table = 'tb_views';

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

    public static function getViewsPage($from_date, $to_date)
    {
        $query = TbViews::select('tb_views.*')
        ->where('tb_views.ngay','>=',$from_date)
        ->where('tb_views.ngay','<=',$to_date)
        ->orderBy('tb_views.ngay','asc')->get();
        
        return $query;
    }

}
