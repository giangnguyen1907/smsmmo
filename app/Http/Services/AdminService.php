<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminService
{
    /**
     * Set SQL to get admin user from table admins
     * @author: ThangNH
     * @param: 
     * - $params: array value to filter
     * - $isPaginate: boolean to paginate results
     * */

    public function getAdmins($params, $isPaginate = false)
    {
        $query = DB::table('admins')
            ->select('admins.*', 'tb_roles.name AS role_name')
            ->when(!empty($params['keyword']), function ($query) use ($params) {

                $keyword = $params['keyword'];

                return $query->where(function ($where) use ($keyword) {
                    return $where->where('admins.email', 'like', '%' . $keyword . '%')
                        ->orWhere('admins.name', 'like', '%' . $keyword . '%');
                });
            })->orderBy('admins.id', 'desc');

        $query->leftJoin('tb_roles', 'admins.role', '=', 'tb_roles.id');

        if ($isPaginate) {
            $limit = Arr::get($params, 'limit', Consts::DEFAULT_PAGINATE_LIMIT);

            return $query->paginate($limit);
        }

        return $query->get();
    }

    // Get all access menu admin by user role
    public static function getAccessMenu()
    {
        $query = DB::table('tb_admin_menus AS a')
            ->selectRaw('a.*, count(b.id) AS submenu')
            ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
            ->where('a.status', Consts::USER_STATUS['active'])
            ->groupBy('a.id')
            ->orderByRaw('a.status ASC, a.iorder ASC, a.id DESC');

        // Admin user is super admin 
        if (Auth::guard('admin')->user()->is_super_admin == 0 || Auth::guard('admin')->user()->is_super_admin == 2 ) {
            $permission = AdminService::getPermisionAccess();
            $query->whereIn('a.id', $permission->menu_id);
        }

        return $query->get();
    }


    public static function getPermisionAccess()
    {
        $role = Role::find(Auth::guard('admin')->user()->role);
        //var_dump($role->json_access->menu_id);die;
        return $role->json_access;
    }
}
