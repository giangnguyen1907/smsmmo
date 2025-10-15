<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\ComponentConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComponentConfigController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'component_configs';
        $this->viewPart = 'admin.pages.component_configs';
        $this->responseData['module_name'] = __('Component Configs');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = ComponentConfig::orderBy('iorder')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] = $rows;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'name' => 'required|max:255',
            'component_code' => 'required|max:255'
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        ComponentConfig::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComponentConfig  $componentConfig
     * @return \Illuminate\Http\Response
     */
    public function show(ComponentConfig $componentConfig)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ComponentConfig  $componentConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(ComponentConfig $componentConfig)
    {
        $this->responseData['detail'] = $componentConfig;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComponentConfig  $componentConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComponentConfig $componentConfig)
    {
        $request->validate([
            'name' => 'required|max:255',
            'component_code' => 'required|max:255'
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $componentConfig->fill($params);
        $componentConfig->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComponentConfig  $componentConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComponentConfig $componentConfig)
    {
        return redirect()->back()->with('errorMessage', __('Record cannot be deleted!'));
    }
}
