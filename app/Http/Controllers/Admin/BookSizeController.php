<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookSize;
use App\Models\OnlineExchangeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ContentService;
use App\Consts;

class BookSizeController extends Controller
{
	public function __construct()
    {   
        $this->routeDefault  = 'booksize';
        $this->viewPart = 'admin.pages.bookSize';
        $this->responseData['module_name'] = __('Thông tin khổ sách');
        
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
        $rows = BookSize::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
		
        BookSize::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookSize  $bookSize
     * @return \Illuminate\Http\Response
     */
    public function show(BookSize $bookSize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookSize  $bookSize
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//dd($id);
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$this->responseData['detail'] = BookSize::find($id);
        return $this->responseView($this->viewPart . '.edit');
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookSize  $bookSize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        //dd($id);
		$bookSize = BookSize::find($id);
		
        $params = $request->all();
        $bookSize->fill($params);
        $bookSize->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookSize  $bookSize
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$bookSize = BookSize::find($id);
		$bookSize->delete();
		return redirect()->back()->with('successMessage', __('Xóa dữ liệu thành công!'));
    }


    public function createAjax(Request $request)
    {
        
        $width = $request->width;
        $height = $request->height;
        /**/
        $params = [];
        $params['width'] = $width;
        $params['height'] = $height;
        $params['weight'] = 1;
        $params['rate_standard'] = 1;
        $params['updated_at'] = date('Y-m-d H:i:s');
        $params['created_at'] = date('Y-m-d H:i:s');
        
        $bookSize = BookSize::create($params);
        
        return $bookSize->id;
        
    }


}
