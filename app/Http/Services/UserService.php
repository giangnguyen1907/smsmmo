<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getUsers($params = [], $isPaginate = false)
    {
        $query = User::select('users.*')
            ->selectRaw('tb_countrys.name AS country, tb_citys.name AS city')

            ->leftJoin('tb_countrys', 'tb_countrys.id', '=', 'users.country_id')
            ->leftJoin('tb_citys', 'tb_citys.id', '=', 'users.city_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('users.email', 'like', '%' . $params['keyword'] . '%')
                        ->orWhere('users.name', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('users.status', $params['status']);
            })
            ->when(!empty($params['user_type']), function ($query) use ($params) {
                return $query->where('users.user_type', $params['user_type']);
            })
            ->when(!empty($params['country_id']), function ($query) use ($params) {
                return $query->where('users.country_id', $params['country_id']);
            });
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('users.' . $key, $value);
                }
            } else {
                $query->orderByRaw('users.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('users.id desc');
        }

        if ($isPaginate) {
            $limit = Arr::get($params, 'limit', Consts::DEFAULT_PAGINATE_LIMIT);
            return $query->paginate($limit);
        }

        return $query->get();
    }
}
