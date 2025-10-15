<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\ContentService;
use App\Models\CmsTaxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsTaxonomyController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'cms_taxonomys';
        $this->viewPart = 'admin.pages.cms_taxonomys';
        $this->responseData['module_name'] = __('CMS Taxonomy');
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
		
		$params_lang['status'] = '1';
        $languages = ContentService::getCmsLanguage($params_lang)->get();
        $this->responseData['languages'] =  $languages;
		
        $params = $request->all();
        $this->responseData['rows'] =  ContentService::getCmsTaxonomy($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

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
        $params_lang['status'] = '1';
        $languages = ContentService::getCmsLanguage($params_lang)->get();
        $this->responseData['languages'] =  $languages;

        // Get all taxonomy is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();

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
            'taxonomy' => 'required|max:255',
        ]);

        $params = $request->all();

        $title = [];
        foreach ($request->input('title') as $language => $translation) {
            $title[$language] = $translation;
        }

        $params['title'] = $title;
		
		$hienthi = isset($params['hienthi']) ? implode(';',$params['hienthi']) : '';
		$params['hienthi'] = ';'.$hienthi.';';
		
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsTaxonomy::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function saveAjax(Request $request)
    {
        
        $id = $request->id;
        $title = $request->title;
        $price = $request->price;
        $iorder = $request->iorder;
        
		$taxonomy = CmsTaxonomy::where('id',$id)->first();

        if($taxonomy){
            
            $taxonomy -> title = $title;
            $taxonomy -> price = $price;
            $taxonomy -> iorder = $iorder;
            $taxonomy -> admin_updated_id = Auth::guard('admin')->user()->id;
            $taxonomy -> save();

        }
		
		return $id;
		/*
		$hienthi = isset($params['hienthi']) ? implode(';',$params['hienthi']) : '';
		$params['hienthi'] = ';'.$hienthi.';';
		
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        CmsTaxonomy::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
		*/
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function show(CmsTaxonomy $cmsTaxonomy)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsTaxonomy $cmsTaxonomy)
    {
		if(ContentService::checkRole($this->routeDefault,'update') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
        $params_lang['status'] = '1';
        $languages = ContentService::getCmsLanguage($params_lang)->get();
        $this->responseData['languages'] =  $languages;
        
        // Get all parents which have status is active
        $params['status'] = Consts::TAXONOMY_STATUS['active'];
        $params['different_id'] = $cmsTaxonomy->id;
        $this->responseData['taxonomys'] = ContentService::getCmsTaxonomy($params)->get();
        $this->responseData['detail'] = $cmsTaxonomy;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsTaxonomy $cmsTaxonomy)
    {
		if(ContentService::checkRole($this->routeDefault,'update') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
        $request->validate([
            'taxonomy' => 'required|max:255',
        ]);
		
        $params = $request->all();
		//dd($params);
		$hienthi = isset($params['hienthi']) ? implode(';',$params['hienthi']) : '';
		
		$params['hienthi'] = ';'.$hienthi.';';
		
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $cmsTaxonomy->fill($params);
        $cmsTaxonomy->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CmsTaxonomy  $cmsTaxonomy
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsTaxonomy $cmsTaxonomy)
    {
		if(ContentService::checkRole($this->routeDefault,'delete') == 0){
			$this->responseData['module_name'] = __('Bạn không có quyền truy cập chức năng này');
			return $this->responseView($this->viewPart . '.404');
		}
        $cmsTaxonomy->status = Consts::STATUS_DELETE;
        $cmsTaxonomy->save();

        // Update delete status sub
        CmsTaxonomy::where('parent_id', '=', $cmsTaxonomy->id)->update(['status' => Consts::STATUS_DELETE]);

        return redirect()->back()->with('successMessage', __('Delete record successfully!'));
    }
}
