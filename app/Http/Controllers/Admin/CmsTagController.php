<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsTag;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Models\Role;
use App\Consts;
use Illuminate\Support\Facades\Auth;

class CmsTagController extends Controller
{
	
	public function __construct()
    {
        $this->routeDefault  = 'cms_tag';
        $this->viewPart = 'admin.pages.cms_tag';
        $this->responseData['module_name'] = __('Quản lý thẻ Tag');
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
		
		$this->responseData['rows'] = ContentService::getCmsTag($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
		
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
			'title' => 'required|max:255',
		]);
		  
		$params = $request->all();
		CmsTag::create($params);
		return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsTag  $cmsTag
     * @return \Illuminate\Http\Response
     */
    public function show(CmsTag $cmsTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsTag  $cmsTag
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsTag $cmsTag)
    {
		if(ContentService::checkRole($this->routeDefault,'edit') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
        $this->responseData['detail'] = $cmsTag;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsTag  $cmsTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsTag $cmsTag)
    {
        if(ContentService::checkRole($this->routeDefault,'edit') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		$request->validate([
            'title' => 'required|max:255',
        ]);
		
        $params = $request->all();
		$cmsTag->fill($params);
        $cmsTag->save();
		return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsTag  $cmsTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsTag $cmsTag)
    {
		if(ContentService::checkRole($this->routeDefault,'delete') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		$cmsTag->delete();
        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
