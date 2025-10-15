<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Services\ContentService;

class AdminMenuController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'admin_menus';
        $this->viewPart = 'admin.pages.admin_menus';
        $this->responseData['module_name'] = 'Quản lý Menu Admin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $rows = AdminMenu::orderByRaw('status ASC, iorder ASC, id DESC')->get();

        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        // Get all menu which have parent_id is null or = 0
        $rootMenus = AdminMenu::where('parent_id', null)->orWhere('parent_id', 0)->orderBy('iorder')->get();

        $this->responseData['rootMenus'] = $rootMenus;

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
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        AdminMenu::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function show(AdminMenu $adminMenu)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminMenu $adminMenu)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        // Get all menu which have parent_id is null or = 0
        $rootMenus = AdminMenu::where('status', 'active')->whereRaw('(parent_id IS null OR parent_id = 0)')->where('id', '!=', $adminMenu->id)->orderBy('iorder')->get();

        $this->responseData['rootMenus'] = $rootMenus;
        $this->responseData['adminMenu'] = $adminMenu;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminMenu $adminMenu)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $adminMenu->fill($params);
        $adminMenu->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminMenu $adminMenu)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $adminMenu->delete();
        // Delete sub
        DB::table('tb_admin_menus')->where('parent_id', '=', $adminMenu->id)->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
