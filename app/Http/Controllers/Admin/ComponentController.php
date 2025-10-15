<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Http\Services\PageBuilderService;
use App\Models\Component;
use App\Models\ComponentConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ComponentController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'components';
        $this->viewPart = 'admin.pages.components';
        $this->responseData['module_name'] = __('Component Management');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $this->responseData['params'] = $params;

        $params['order_by'] = [
            'status' => 'ASC',
            'iorder' => 'ASC',
            'id' => 'DESC'
        ];

        $rows = PageBuilderService::getComponent($params)->get();
        $this->responseData['rows'] =  $rows;

        // Get all component_configs which have status is active
        $component_configs = ComponentConfig::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['component_configs'] = $component_configs;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all component_configs which have status is active
        $component_configs = ComponentConfig::where('status', 'active')->orderByRaw('iorder ASC, id DESC')->get();
        $this->responseData['component_configs'] = $component_configs;

        return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        $params = $request->all();

        $params['parent_id'] = $params['parent_id'] ?? NULL;
        $params['iorder'] = $params['iorder'] ?? (Component::where('parent_id', $params['parent_id'])->where('status', '!=', Consts::STATUS_DELETE)->max('iorder') + 1);

        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $component = Component::create($params);

        $id_redirect = $component->parent_id ?? $component->id;

        return redirect()->route($this->routeDefault . '.edit', $id_redirect)->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function show(Component $component)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function edit(Component $component)
    {
        // Get all child items
        $items = Component::where('status', 'active')->where('parent_id', $component->id)->orderByRaw('iorder ASC, id DESC')->get();

        $this->responseData['items'] = $items;
        $this->responseData['detail'] = $component;
        
        $this->responseData['parents'] = $items;
        $this->responseData['component_configs'] = $items;
        
        if (View::exists($this->viewPart . '.edit.' . $component->component_code)) {
            return $this->responseView($this->viewPart . '.edit.' . $component->component_code);
        }
        return $this->responseView($this->viewPart . '.edit.default');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Component $component)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);
        $params = $request->all();

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $component->fill($params);
        $component->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Component  $component
     * @return \Illuminate\Http\Response
     */
    public function destroy(Component $component)
    {
        // Update status to delete
        $component->status = Consts::STATUS_DELETE;
        $component->save();

        // Delete sub
        // DB::table('tb_components')->where('parent_id', '=', $component->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
