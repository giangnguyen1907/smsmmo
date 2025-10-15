<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsAuthor;
use Illuminate\Http\Request;
use App\Http\Services\ContentService;
use App\Models\Role;
use App\Consts;
use Illuminate\Support\Facades\Auth;

class CmsAuthorController extends Controller
{
	public function __construct()
    {
        $this->routeDefault  = 'cms_author';
        $this->viewPart = 'admin.pages.cms_author';
        $this->responseData['module_name'] = __('Quản lý tác giả');
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
		
		$this->responseData['rows'] = ContentService::getCmsAuthor($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
		
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
		CmsAuthor::create($params);
		return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CmsAuthor  $cmsAuthor
     * @return \Illuminate\Http\Response
     */
    public function show(CmsAuthor $cmsAuthor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CmsAuthor  $cmsAuthor
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsAuthor $cmsAuthor)
    {
        //
		if(ContentService::checkRole($this->routeDefault,'create') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		$this->responseData['detail'] = $cmsAuthor;
		return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CmsAuthor  $cmsAuthor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsAuthor $cmsAuthor)
    {
        if(ContentService::checkRole($this->routeDefault,'edit') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		
        $params = $request->all();
		$cmsAuthor->fill($params);
        $cmsAuthor->save();
		return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CmsAuthor  $cmsAuthor
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsAuthor $cmsAuthor)
    {
        if(ContentService::checkRole($this->routeDefault,'delete') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
		$cmsAuthor->delete();
        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }

    public function createAuthor(Request $request)
    {
        
        $title = $request->title;
        $birthday = $request->birthday;
        $description = $request->description;
        /**/
        $params = [];
        $params['title'] = $title;
        $params['birthday'] = $birthday;
        $params['description'] = $description;
        $params['updated_at'] = date('Y-m-d H:i:s');
        $params['created_at'] = date('Y-m-d H:i:s');
        
        $cmsAuthor = CmsAuthor::create($params);
        
        return $cmsAuthor->id;
        
    }

}
