<?php

namespace App\Http\Controllers\Admin;

use App\Models\ModuleFunction;
use Illuminate\Http\Request;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class ModuleFunctionController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'module_functions';
        $this->viewPart = 'admin.pages.module_functions';
        $this->responseData['module_name'] = 'Quản lý chức năng hệ thống';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::orderByRaw('status ASC, iorder ASC, id DESC')->get();

        $rows = ModuleFunction::orderByRaw('status ASC, iorder ASC, id DESC')->get();

        $this->responseData['rows'] = $rows;
        $this->responseData['modules'] = $modules;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = Module::orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['modules'] = $modules;

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
            'name' => 'required',
            'function_code' => 'required|unique:tb_module_functions|max:255',
            'module_id' => 'required',
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        ModuleFunction::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ModuleFunction  $moduleFunction
     * @return \Illuminate\Http\Response
     */
    public function show(ModuleFunction $moduleFunction)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ModuleFunction  $moduleFunction
     * @return \Illuminate\Http\Response
     */
    public function edit(ModuleFunction $moduleFunction)
    {
        $modules = Module::orderByRaw('status ASC, iorder ASC, id DESC')->get();

        $this->responseData['modules'] = $modules;
        $this->responseData['moduleFunction'] = $moduleFunction;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ModuleFunction  $moduleFunction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModuleFunction $moduleFunction)
    {
        $request->validate([
            'name' => 'required|max:255',
            'function_code' => 'required|max:255|unique:tb_module_functions,function_code,' . $moduleFunction->id,
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $moduleFunction->fill($params);
        $moduleFunction->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ModuleFunction  $moduleFunction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModuleFunction $moduleFunction)
    {
        $moduleFunction->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
