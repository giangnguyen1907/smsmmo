<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Consts;
use App\Models\AdminMenu;
use App\Models\ModuleFunction;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ContentService;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'roles';
        $this->viewPart = 'admin.pages.roles';
        $this->responseData['module_name'] = 'Quản lý nhóm quyền hệ thống';
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

        $rows = Role::orderByRaw('status ASC, iorder ASC, id DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

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
        
        if(ContentService::checkRole($this->routeDefault,'create') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $activeMenus = AdminMenu::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $activeModules = Module::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $activeFunctions = ModuleFunction::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();

        $this->responseData['activeModules'] = $activeModules;
        $this->responseData['activeFunctions'] = $activeFunctions;
        $this->responseData['activeMenus'] = $activeMenus;

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
        $array_params = $request->json_action;
        //dd($array_params);
        $params = $request->only([
            'name',
            'description',
            'iorder',
            'status',
            'json_access',
        ]);
        //dd($params);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $role = Role::create($params);

        $check_user_role = UserRole::where('role_id',$role->id)->first();
        if($check_user_role){
            $check_user_role -> json_action = $array_params;
            $check_user_role -> admin_updated_id = Auth::guard('admin')->user()->id;
            $check_user_role -> save();
        }else{
            $check_user_role = new UserRole();
            $check_user_role -> role_id = $role->id;
            $check_user_role -> json_action = $array_params;
            $check_user_role -> admin_created_id = Auth::guard('admin')->user()->id;
            $check_user_role -> admin_updated_id = Auth::guard('admin')->user()->id;
            $check_user_role -> save();
        }
        

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $activeMenus = AdminMenu::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $activeModules = Module::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $activeFunctions = ModuleFunction::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        
        $check_user_role = UserRole::where('role_id',$role->id)->first();

        $this->responseData['activeModules'] = $activeModules;
        $this->responseData['activeFunctions'] = $activeFunctions;
        $this->responseData['activeMenus'] = $activeMenus;
        $this->responseData['detail'] = $role;
        $this->responseData['check_action'] = $check_user_role;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $request->validate([
            'name' => 'required|max:255',
        ]);

        $array_params = $request->json_action;

        $check_user_role = UserRole::where('role_id',$role->id)->first();
        if($check_user_role){
            $check_user_role -> json_action = $array_params;
            $check_user_role -> admin_updated_id = Auth::guard('admin')->user()->id;
            $check_user_role -> save();
        }else{
            $check_user_role = new UserRole();
            $check_user_role -> role_id = $role->id;
            $check_user_role -> json_action = $array_params;
            $check_user_role -> admin_created_id = Auth::guard('admin')->user()->id;
            $check_user_role -> admin_updated_id = Auth::guard('admin')->user()->id;
            $check_user_role -> save();
        }

        //dd($array_params);
        //dd($request->json_access);
        $params = $request->only([
            'name',
            'description',
            'iorder',
            'status',
            'json_access',
        ]);

        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $role->fill($params);
        $role->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {

        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }

        $role_id = $role->id;

        UserRole::where('role_id',$role_id)->delete();

        $role->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
