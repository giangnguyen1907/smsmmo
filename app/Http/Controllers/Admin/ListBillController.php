<?php

namespace App\Http\Controllers\Admin;

use App\Models\ListBill;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;

class ListBillController extends Controller
{
	public function __construct()
    {
        $this->routeDefault  = 'listbill';
        $this->viewPart = 'admin.pages.listbill';
		$this->locale = 'vi';
        $this->responseData['module_name'] = __('Loại phiếu');
		//$this->array_istype = array(0=>'Phiếu nhập',1=>'Phiếu xuất');
		$this->responseData['array_istype'] = array(0=>'Phiếu nhập',1=>'Phiếu xuất');
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
		
		$rows = ContentService::getListBill($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
		$this->responseData['rows'] = $rows;
		//$this->responseData['array_istype'] = $this->array_istype;
		
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
		//$this->responseData['array_istype'] = $this->array_istype;
		$this->responseData['module_name'] = __('Thêm mới loại phiếu');
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
		
		ListBill::create($params);

		
		return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ListBill  $listBill
     * @return \Illuminate\Http\Response
     */
    public function show(ListBill $listBill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ListBill  $listBill
     * @return \Illuminate\Http\Response
     */
    public function edit(ListBill $listBill,$id)
    {
		//dd($listBill);
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$listBill = ListBill::find($id);
		//$this->responseData['array_istype'] = $this->array_istype;
        $this->responseData['detail'] = $listBill;
        return $this->responseView($this->viewPart . '.edit');
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ListBill  $listBill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ListBill $listBill, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
		$listBill = ListBill::find($id);
		
        $params = $request->all();
		
        $listBill->fill($params);
        $listBill->save();
		
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ListBill  $listBill
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$listBill = ListBill::find($id);
		$listBill->delete();
		return redirect()->back()->with('successMessage', __('Xóa dữ liệu thành công!'));
    }
}
