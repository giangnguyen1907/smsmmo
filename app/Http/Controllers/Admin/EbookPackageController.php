<?php

namespace App\Http\Controllers\Admin;

use App\Models\EbookPackage;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;

class EbookPackageController extends Controller
{
	public function __construct()
    {   
        $this->routeDefault  = 'ebookPackage';
        $this->viewPart = 'admin.pages.ebookPackage';
        $this->responseData['module_name'] = __('Thông tin gói thuê ebook');
		
		// $this->array_recipe = array(1=>'Giá 1 trang * Tổng số trang (ebook)',2=>'Theo x% Giá bán sách giấy',3=>'Tự nhập giá',4=>'Giá * kích thước file',5=>'Theo x% Giá bìa sách giấy');
		$this->array_recipe = array(1=>'Giá 1 trang * Tổng số trang (ebook)',2=>'Theo x% Giá bán sách giấy',3=>'Tự nhập giá');
		$this->array_booktype = array(1=>'Ebook',2=>'Audio',3=>'Video');
		$this->array_rounding = array(1=>'Hàng đơn vị',10=>'Hàng chục',100=>'Hàng trăm',1000=>'Hàng nghìn');
		
        
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
        $rows = EbookPackage::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
		$this->responseData['array_recipe'] = $this->array_recipe;
		$this->responseData['array_booktype'] = $this->array_booktype;
		$this->responseData['array_rounding'] = $this->array_rounding;
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
		$this->responseData['array_recipe'] = $this->array_recipe;
		$this->responseData['array_booktype'] = $this->array_booktype;
		$this->responseData['array_rounding'] = $this->array_rounding;
		$this->responseData['module_name'] = __('Thêm mới khổ sách');
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
		
        EbookPackage::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EbookPackage  $ebookPackage
     * @return \Illuminate\Http\Response
     */
    public function show(EbookPackage $ebookPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EbookPackage  $ebookPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(EbookPackage $ebookPackage)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        //dd($ebookPackage);
		$this->responseData['array_recipe'] = $this->array_recipe;
		$this->responseData['array_booktype'] = $this->array_booktype;
		$this->responseData['array_rounding'] = $this->array_rounding;
        $this->responseData['detail'] = $ebookPackage;
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EbookPackage  $ebookPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EbookPackage $ebookPackage)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $params = $request->all();
        $ebookPackage->fill($params);
        $ebookPackage->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EbookPackage  $ebookPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(EbookPackage $ebookPackage)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		
		$ebookPackage->status = 0;
		$ebookPackage->save();
		
		return redirect()->back()->with('successMessage', __('Đã chuyển về trạng thái không hoạt động'));
    }
}
