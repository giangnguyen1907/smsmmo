<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\BlockContent;
use App\Models\CmsBlog;
use App\Models\Component;
use App\Models\Widget;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PageBuilderService
{
    public static function getBlockContent($params = [])
    {
		
        $query = BlockContent::select('tb_block_contents.*')
            ->selectRaw('count(b.id) AS sub, tb_blocks.name AS block_name')
            ->leftJoin('tb_block_contents AS b', 'tb_block_contents.id', '=', 'b.parent_id')
            ->leftJoin('tb_blocks', 'tb_blocks.block_code', '=', 'tb_block_contents.block_code')
            ->groupBy('tb_block_contents.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_block_contents.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_block_contents.title->vi', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['block_code']), function ($query) use ($params) {
                $query->where('tb_block_contents.block_code', '=', $params['block_code']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_blocks.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_block_contents.status', $params['status']);
        } else {
            $query->where('tb_block_contents.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_block_contents.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_block_contents.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_block_contents.id desc');
        }

        return $query;
    }

    public static function getBlog($params = [])
    {
		
        $query = CmsBlog::select('tb_cms_blog.*')
            ->groupBy('tb_cms_blog.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_cms_blog.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_cms_blog.title->vi', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['blog_id']), function ($query) use ($params) {
                $query->where('tb_cms_blog.blog_id', '=', $params['blog_id']);
            })
            ->when(!empty($params['alias']), function ($query) use ($params) {
                $query->where('tb_cms_blog.alias', '=', $params['alias']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_blocks.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_cms_blog.status', $params['status']);
        } else {
            $query->where('tb_cms_blog.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_blog.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_blog.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_blog.id desc');
        }

        return $query;
    }

    public static function getComponent($params = [])
    {
        $query = Component::select('tb_components.*')
            ->selectRaw('count(b.id) AS sub, tb_component_configs.name AS component_name')
            ->leftJoin('tb_components AS b', 'tb_components.id', '=', 'b.parent_id')
            ->leftJoin('tb_component_configs', 'tb_component_configs.component_code', '=', 'tb_components.component_code')
            ->groupBy('tb_components.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_components.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_components.title', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['component_code']), function ($query) use ($params) {
                $query->where('tb_components.component_code', '=', $params['component_code']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_component_configs.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_components.status', $params['status']);
        } else {
            $query->where('tb_components.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_components.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_components.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_components.id desc');
        }

        return $query;
    }

    public static function getWidget($params = [])
    {
        $query = Widget::select('tb_widgets.*')
            ->selectRaw('tb_widget_configs.name AS widget_name')
            ->leftJoin('tb_widget_configs', 'tb_widget_configs.widget_code', '=', 'tb_widgets.widget_code')
            ->groupBy('tb_widgets.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_widgets.id', '=', $params['id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_widgets.title', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['widget_code']), function ($query) use ($params) {
                $query->where('tb_widgets.widget_code', '=', $params['widget_code']);
            })
            ->when(!empty($params['template']), function ($query) use ($params) {
                $query->whereJsonContains('tb_widget_configs.json_params->template', $params['template']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_widgets.status', $params['status']);
        } else {
            $query->where('tb_widgets.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_widgets.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_widgets.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_widgets.id desc');
        }

        return $query;
    }
}
