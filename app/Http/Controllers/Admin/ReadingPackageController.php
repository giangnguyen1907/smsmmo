<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReadingPackage;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Consts;

class ReadingPackageController extends Controller
{
	
	public function __construct()
    {   
        $this->routeDefault  = 'readingpackage';
        $this->viewPart = 'admin.pages.readingpackage';
        $this->responseData['module_name'] = __('Loại thành viên');
        $this->locale = 'vi';
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
        $rows = ReadingPackage::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
		
		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$paramTaxonomys['taxonomy'] = 'document';
		$this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
		$this->responseData['locale'] = $this->locale;
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
		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$paramTaxonomys['taxonomy'] = 'document';
		$this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
        $this->responseData['module_name'] = __('Thêm mới loại thành viên');
        $this->responseData['locale'] = $this->locale;
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
		
		if(isset($params['category'])){
			$params['category'] = ','.implode(',',$params['category']).',';
		}else{
			$params['category'] = '';
		}
		
		ReadingPackage::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReadingPackage  $readingPackage
     * @return \Illuminate\Http\Response
     */
    public function show(ReadingPackage $readingPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReadingPackage  $readingPackage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		//dd($readingPackage);
		
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$this->responseData['locale'] = $this->locale;
		$paramTaxonomys['status'] = Consts::TAXONOMY_STATUS['active'];
		$paramTaxonomys['taxonomy'] = 'document';
		$this->responseData['parents'] = ContentService::getCmsTaxonomy($paramTaxonomys)->get();
		
		$this->responseData['detail'] = ReadingPackage::find($id);
		
        return $this->responseView($this->viewPart . '.edit');
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReadingPackage  $readingPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(ContentService::checkRole($this->routeDefault,'update') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
        //dd($id);
		$readingPackage = ReadingPackage::find($id);
		
        $params = $request->all();
		
		if(isset($params['category'])){
			$params['category'] = ','.implode(',',$params['category']).',';
		}else{
			$params['category'] = '';
		}
		
        $readingPackage->fill($params);
        $readingPackage->save();
        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReadingPackage  $readingPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
            $this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
            return $this->responseView($this->viewPart . '.404');
        }
		$readingPackage = ReadingPackage::find($id);
		$readingPackage->delete();
		return redirect()->back()->with('successMessage', __('Xóa dữ liệu thành công!'));
    }
}
