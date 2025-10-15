<?php

namespace App\Http\Controllers\Admin;

use App\Models\ManagerShop;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;

class ManagerShopController extends Controller
{
	public function __construct()
    {   
        $this->routeDefault  = 'managershop';
        $this->viewPart = 'admin.pages.managershop';
        $this->responseData['module_name'] = __('Cấu hình nhà xuât bản');
        $this->responseData['locale'] = 'vi';
		
		$this->responseData['array_code'] = array(1=>'Quản lý xưởng',2=>'Quản lý kho sách',3=>'Nhà phát hành',4 => 'Đối tượng xuất kho');
		
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(ContentService::checkRole($this->routeDefault,'index') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        $params = $request->all();
        $rows = ManagerShop::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
		$this->responseData['module_name'] = __('Thêm mới cấu hình');
        
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
		
		$params = $request->all();
		
		ManagerShop::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ManagerShop  $managerShop
     * @return \Illuminate\Http\Response
     */
    public function show(ManagerShop $managerShop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ManagerShop  $managerShop
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//dd($managerShop);
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        
		$managerShop = ManagerShop::find($id);
        $this->responseData['detail'] = $managerShop;
        return $this->responseView($this->viewPart . '.edit');
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManagerShop  $managerShop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        
		$managerShop = ManagerShop::find($id);
		
        $params = $request->all();
		
        $managerShop->fill($params);
        $managerShop->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManagerShop  $managerShop
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$managerShop = ManagerShop::find($id);
		$managerShop->delete();
		return redirect()->back()->with('successMessage', __('Xóa dữ liệu thành công!'));
    }
}
